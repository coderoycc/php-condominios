<?php

namespace App\Controllers;

use App\Models\Shipping;
use App\Providers\DBAppProvider;
use Helpers\Resources\Response;

class ShippingController {
  public function create($data, $files = null) /* protected */ {
    $con = DBAppProvider::get_conecction();
    $idUser = DBAppProvider::get_payload_value('user_id');
    $subscription = DBAppProvider::get_sub();
    $shipping = new Shipping($con);
    $data['status'] = 'PENDIENTE';
    $data['created_by'] = $idUser;
    $data['department_id'] = $subscription->department_id;
    $shipping->set_data($data);
    if ($shipping->insert() > 0) {
      Response::success_json('Creado con exito', ['shipping' => $shipping], 201);
    } else {
      Response::error_json(['message' => 'Error al crear el shipping']);
    }
  }
  public function get($query) /*protected*/ {
    if (!isset($query['id']))
      Response::error_json(['message' => 'Error, ID faltante']);
    $con = DBAppProvider::get_conecction();
    $shipping = new Shipping($con, $query['id']);
    // var_dump($shipping);
    if ($shipping->id != 0)
      Response::success_json('OK', ['shipping' => $shipping]);
    else
      Response::error_json(['message' => 'Error, no se encontro el shipping'], 404);
  }
  public function me($query)/* protected */ {
    $con = DBAppProvider::get_conecction();

    $subscription = DBAppProvider::get_sub();
    $shippings = Shipping::get_all($con, ['department_id' => $subscription->department_id]);
    Response::success_json('OK', ['shippings' => $shippings]);
  }
}
