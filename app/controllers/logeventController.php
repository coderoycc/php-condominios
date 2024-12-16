<?php

namespace App\Controllers;

use App\Models\Logevent;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

use function App\Services\event;

class LogEventController {
  public function no_seen($query) {
    $logs = Logevent::all($query['qty'] ?? 3, ['no_seen' => true]);
    Response::success_json('OK', $logs, 200);
  }
  public function seen($body) {
    if (!Request::required(['id'], $body))
      Response::error_json(['message' => 'Faltan datos'], 200);

    $event = new Logevent($body['id']);
    $event->seen = $event->seen == 0 ? 1 : 0;
    $event->update_seen();
    Response::success_json('OK', $event, 200);
  }
  public function test($query) {
    $pela = $query['asdfasdf'];
    event()->notify(['message' => 'En evento', 'new' => 'asdfasdf', 'del' => 3]);
    Response::success_json('OK', $pela, 200);
  }
}
