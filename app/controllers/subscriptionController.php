<?php

namespace App\Controllers;

use App\Models\Subscription;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class SubscriptionController {
  public function types($data) {
    if (!Request::required(['pin'], $data))
      Response::error_json(['message' => 'Datos faltantes: [pin] requerido']);
    $data = Subscription::getTypes($data['pin']);
    Response::success_json('Tipos de suscripción', $data);
  }
}
