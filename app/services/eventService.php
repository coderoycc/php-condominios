<?php

namespace App\Services;

use App\Models\Logevent;
use App\Models\TokenEvent;
use DateTime;
use ElephantIO\Client;
use Error;
use PDO;
use Throwable;

use function App\Providers\logger;

use const App\Config\URL_WEBSOCKET;
use const App\Config\WEBSOCKET_PORT;

require_once(__DIR__ . '/../models/tokenEvent.php');
class EventService {
  private function check_connection() {
    try {
      $connected = @fsockopen(URL_WEBSOCKET, WEBSOCKET_PORT);
      if ($connected) {
        fclose($connected);
        return true;
      }
      return false;
    } catch (Throwable $th) {
      return false;
    }
  }
  /**
   * Agrega un evento de password olvidado
   * @param PDO $con
   * @param int $user_id
   * @return TokenEvent
   */
  public function forgottenpass($con, $user_id) {
    $event = new TokenEvent($con);
    $event->user_id = $user_id;
    $event->event = 'forgotenpass';
    $event->new();
    return $event;
  }
  public function verify($con, $user_id, $token) {
    $event = TokenEvent::verify($con, $user_id, $token);
    $response = ['success' => false, 'message' => ''];
    if ($event->id > 0) {
      // fecha
      $today = new DateTime();
      $expire = new DateTime($event->expires_at);
      if ($today->getTimestamp() < $expire->getTimestamp()) {
        if ($event->used == 0) {
          $event->used = 1;
          $event->update_used();
          $response['success'] = true;
          $response['message'] = 'Token válido';
        } else
          $response['message'] = 'Token ya utilizado';
      } else
        $response['message'] = 'Token expirado';
    } else
      $response['message'] = 'Token no válido';
    return $response;
  }
  /**
   * Notifica con socketio a los administradores (web - navegador)
   * @param mixed $data datos a enviar {array}
   * @return void
   */
  public function notify($data) {
    $url = URL_WEBSOCKET . ':' . WEBSOCKET_PORT;
    try {
      $options = ['client' => Client::CLIENT_4X, 'timeout' => 3];
      if (!$this->check_connection()) {
        logger()->error(new Error('No hay conexion al websocket'));
        return;
      }
      $client = Client::create($url, $options);
      $client->connect();
      $client->emit('send-master', $data);
      $client->disconnect();
    } catch (Throwable $th) {
      logger()->error($th);
    }
  }
  /**
   * Agregar un nuevo evento en base de datos
   * @param string $event evento a registrar
   * @param string $pin pin del condominio al que pertenece el evento
   * @param string $target entidad al que hace referencia el evento  
   * @param string $type success | info | danger
   * @return Logevent
   */
  public function new($event, $detail, $pin, $target, $type) {
    $logEvent = new Logevent();
    $logEvent->event = $event;
    $logEvent->event_detail = $detail;
    $logEvent->pin = $pin;
    $logEvent->target = $target;
    $logEvent->type = $type;
    $logEvent->save();
    return $logEvent;
  }
  public function all_logs($seen) {
    if ($seen == '0')
      return Logevent::all(15, ['no_seen' => true]);
    else if ($seen == '1')
      return Logevent::all(15, ['seen' => true]);
    return Logevent::all();
  }
}

if (!function_exists('event')) {
  function event() {
    return new EventService();
  }
}
