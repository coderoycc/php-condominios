<?php

namespace App\Services;

use App\Models\TokenEvent;
use ElephantIO\Client;
use PDO;
use Throwable;

use function App\Providers\logger;

use const App\Config\URL_WEBSOCKET;

require_once(__DIR__ . '/../models/tokenEvent.php');
class EventService {
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
  /**
   * Notifica con socketio a los administradores (web - navegador)
   * @param mixed $data datos a enviar {array}
   * @return void
   */
  public function notify($data) {
    $url = URL_WEBSOCKET;
    try {
      $options = ['client' => Client::CLIENT_4X];
      $client = Client::create($url, $options);
      $client->connect();
      $client->emit('send-master', $data);
      $client->disconnect();
    } catch (Throwable $th) {
      logger()->error($th);
    }
  }
}

if (!function_exists('event')) {
  function event() {
    return new EventService();
  }
}
