<?php

namespace App\Utils;

require_once(__DIR__ . '/../../providers/qrDataProvider.php');

use App\Models\Payment;
use App\Providers\QrDataProvider;

use const App\Config\EXPIRATION_QR;
use const App\Config\URLBASE_API_QR;

/**
 * Clase que maneja las credenciales de pago de la aplicacion todo se ejecuta con CURL, este maneja una instancia
 */
class HandlerPays {
  private $instance = null;
  public function __construct($method = 'POST') {
    $this->instance = curl_init();
    curl_setopt($this->instance, CURLOPT_URL, URLBASE_API_QR . '/Generated');
    curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->instance, CURLOPT_CUSTOMREQUEST, $method);
    // curl_setopt($this->instance, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1 | CURL_SSLVERSION_TLSv1_1 | CURL_SSLVERSION_TLSv1_3);
  }
  /**
   * 
   * @param Payment $payment
   * @param array $condominio
   * @param string $account
   * @param boolean $singleuse
   * @param boolean $collector datos a enviar para recibir en el rollback
   * @return HandlerPays
   */
  public function load($payment, $condominio, $account, $singleuse = true) {
    // obtener variables de acuerdo a la cuenta
    $data = new QrDataProvider($account); // cuenta 1
    $collector = [
      [
        "name" => "pay_id",
        "parameter" => "number",
        "value" => $payment->idPayment
      ],
      [
        "name" => "key",
        "parameter" => "string",
        "value" => $condominio['pin']
      ]
    ];
    $body = [
      'currency' => $payment->currency,
      'amount' => $payment->amount,
      'gloss' => $payment->gloss,
      'serviceCode' => $payment->serviceCode,
      'sigleUse' => $singleuse,
      'enableBank' => 'ALL',
      'city' => $condominio['city'],
      "branchOffice" => 'Condominio ' . $condominio['name'],
      "teller" => "Caja 1",
      'phoneNumber' => $condominio['phone'] ?? '000000',
      'expiration' => EXPIRATION_QR,
      'collectors' => $collector
    ];
    $body = array_merge($body, $data->get_body());
    $body = json_encode($body);
    curl_setopt($this->instance, CURLOPT_POSTFIELDS, $body);
    curl_setopt(
      $this->instance,
      CURLOPT_HTTPHEADER,
      [
        'Content-Type: application/json',
        'Correlation-Id:' . $payment->correlation_id
      ]
    );
    curl_setopt($this->instance, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($this->instance, CURLOPT_USERPWD, $data->get_auth());
    curl_setopt($this->instance, CURLOPT_SSLCERTTYPE, 'PEM');
    curl_setopt($this->instance, CURLOPT_SSLCERT, $data->get_cert());
    curl_setopt($this->instance, CURLOPT_SSLKEY, $data->get_cert());
    // curl_setopt($this->instance, CURLOPT_CAINFO, __DIR__ . '/../../config/cacert.pem');
    curl_setopt($this->instance, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($this->instance, CURLOPT_SSL_VERIFYPEER, true);

    // curl_setopt($this->instance, CURLOPT_SSLCERTPASSWD, $data->get_cert_pass());
    return $this;
  }
  public function pay() {

    try {
      $response = curl_exec($this->instance);
      var_dump($response);
      if (curl_errno($this->instance)) {
        throw new \Exception(curl_error($this->instance));
      }
      return json_decode($response, true);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
