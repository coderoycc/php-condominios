<?php

namespace App\Controllers;

use App\Models\Subscriptiontype;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsUnprocessable;

class SubscriptiontypeController {
  public function down_up($body)/* web */ {
    if (!Request::required(['id'], $body))
      Response::error_json(['message' => 'Ocurrio un error'], 299);
    $con = DBWebProvider::getSessionDataDB();
    $type = new Subscriptiontype($con, $body['id']);
    if ($type->id > 0) {
      $type->status = $type->status == 0 ? 1 : 0;
      $res = Subscriptiontype::update_all($type);
      if ($res)
        Response::success_json('Se actualizo el tipo', $type, 200);
      else
        Response::error_json(['message' => 'No se actualizo el tipo', 'errorcode' => '500 '], 299);
    }
    Response::error_json(['message' => 'No se encontro el tipo', 'errorcode' => '404 Not Found'], 299);
  }
  public function type_form($query)/*web*/ {
    if (!Request::required(['id'], $query))
      Render::view('error_html', ['message' => 'Parametro ID requerido', 'message_detail' => 'No existe el tipo por el ID']);

    $con = DBWebProvider::getSessionDataDB();
    $type = new Subscriptiontype($con, $query['id']);
    Render::view('subscription/edit_type_form', ['type' => $type]);
  }
  public function update($body)/*web*/ {
    if (!Request::required(['id', 'name', 'description'], $body))
      Response::error_json(['message' => 'Datos faltantes'], 299);

    $type = new Subscriptiontype(DBWebProvider::getSessionDataDB(), $body['id']);
    if ($type->id > 0) {
      $type->name = $body['name'];
      $type->tag = $body['tag'] ?? $type->tag;
      $type->description = $body['description'] ?? $type->description;
      $type->price = $body['price'] ?? 0;
      $type->max_users = $body['max_users'];
      $type->annual_price = $body['annual_price'] ?? 0;
      $type->courrier = isset($body['courrier']) ? 1 : 0;
      $type->see_services = isset($body['see_services']) ? 1 : 0;
      $type->see_lockers = isset($body['see_lockers']) ? 1 : 0;
      $res = Subscriptiontype::update_all($type);
      if ($res) {
        Response::success_json('Se actualizo el tipo', $res, 200);
      } else
        Response::error_json(['message' => 'No se actualizo el tipo', 'errorcode' => '500 '], 299);
    }
    Response::error_json(['message' => 'No se encontro el tipo', 'errorcode' => '404 Not Found'], 299);
  }
}
