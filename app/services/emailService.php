<?php

namespace App\Services;

require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

use App\Models\Master;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

use function App\Providers\logger;

use const App\Config\MAIL_PORT;
use const App\Config\MAIL_PWD;

class EmailService {
  private $instance = null;
  public function __construct() {
    $this->instance = new PHPMailer(true);
    $config = Master::config_data();
    if (!isset($config['host_email']) || !isset($config['email']) || !isset($config['name']))
      throw new Exception('No se ha configurado el correo electronico');

    logger()->debug(json_encode($config));
    $this->instance->SMTPDebug = 0;
    // $this->instance->SMTPDebug = 2; // Cambia a 3 o 4 para más información
    $this->instance->Debugoutput = 'html'; // O 'error_log' para registros
    $this->instance->isSMTP();
    $this->instance->Host = $config['host_email'];
    $this->instance->Port = MAIL_PORT;
    $this->instance->SMTPSecure = 'tls';
    $this->instance->SMTPAuth = true;
    $this->instance->Username = $config['email'];
    $this->instance->Password = MAIL_PWD;
    $this->instance->setFrom($config['email'], $config['name']);
    $this->instance->SMTPOptions = [
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    ];
  }
  /**
   * Enviar un correo electronico 
   * @param string $message cadena simple o html cuerpo del correo
   * @param boolean $html tipo de {message} si es html o no, false por defecto
   * @param mixed $to correo electronico destino
   * @param mixed $subject asunto del correo
   * @return bool
   */
  public function send($message, $html =  false, $to, $subject) {
    try {
      $this->instance->addAddress($to);
      $this->instance->Subject = $subject;
      $this->instance->Body    = $message;
      $this->instance->isHTML($html);
      $res = $this->instance->send();
      if ($res)
        return true;
      else {
        var_dump($this->instance->ErrorInfo);
        logger()->debug($this->instance->ErrorInfo);
        return false;
      }
    } catch (Throwable $th) {
      logger()->error($th);
    }
    return false;
  }
}


if (!function_exists('email')) {
  function email() {
    return new EmailService();
  }
}
