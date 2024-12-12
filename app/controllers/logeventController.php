<?php

namespace App\Controllers;

use App\Models\Logevent;
use Helpers\Resources\Response;

class LogEventController {
  public function no_seen($query) {
    $logs = Logevent::all($query['qty'] ?? 3, ['no_seen' => true]);
    Response::success_json('OK', $logs, 200);
  }
}
