<?php

namespace App\Controllers;

use App\Models\Subscription;
use App\Providers\DBWebProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class SubscriptionController {
  public function types($data) {
    if (!Request::required(['pin'], $data))
      Response::error_json(['message' => 'Datos faltantes: [pin] requerido']);
    $data = Subscription::getTypes($data['pin']);
    Response::success_json('Tipos de suscripción', $data);
  }
  public function types_web($data) {
    $condominio = DBWebProvider::session_get_condominio();
    if(isset($condominio->pin)){
      $data = Subscription::getTypes($condominio->pin);
      ob_start();
      include(__DIR__.'/../views/subscription/type_list.php');
      $html = ob_get_clean();
      Response::html($html);
    }else{
      Response::html("<h1 class='text-center'>Ocurrió un error en instance conection</h1>");
    }
  }
}
