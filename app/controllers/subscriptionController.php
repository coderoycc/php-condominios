<?php

namespace App\Controllers;

use App\Models\Subscription;
use Helpers\Resources\Response;

class SubscriptionController {
  public function types($data) {
    $data = Subscription::getTypes($data['pin']);
    Response::success_json(['data' => $data, 'message' => 'Correcto']);
  }
}
