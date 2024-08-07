<?php

namespace App\Controllers;

use App\Models\Shipping;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class ShippingController {
  public function create($data, $files = null) /* protected */ {
    $con = DBAppProvider::get_connection();
    $idUser = DBAppProvider::get_payload_value('user_id');
    $subscription = DBAppProvider::get_sub();
    $shipping = new Shipping($con);
    $data['status'] = 'PENDIENTE';
    $data['created_by'] = $idUser;
    $data['department_id'] = $subscription->department_id;
    $shipping->set_data($data);
    if ($shipping->insert() > 0) {
      $shipping->created_at = date('Y-m-d H:i:s');
      Response::success_json('Creado con exito', ['shipping' => $shipping], 201);
    } else {
      Response::error_json(['message' => 'Error al crear el shipping']);
    }
  }
  public function get($query) /*protected*/ {
    if (!isset($query['id']))
      Response::error_json(['message' => 'Error, ID faltante']);
    $con = DBAppProvider::get_connection();
    $shipping = new Shipping($con, $query['id']);
    // var_dump($shipping);
    if ($shipping->id != 0)
      Response::success_json('OK', ['shipping' => $shipping]);
    else
      Response::error_json(['message' => 'Error, no se encontro el shipping'], 404);
  }
  public function me($query)/* protected */ {
    $con = DBAppProvider::get_connection();

    $subscription = DBAppProvider::get_sub();
    $shippings = Shipping::get_all($con, ['department_id' => $subscription->department_id]);
    Response::success_json('OK', ['shippings' => $shippings]);
  }
  public function update($data)/* protected */ {
    if (!Request::required(['id'], $data))
      Response::error_json(['message' => 'Error, el ID es necesario']);

    $con = DBAppProvider::get_connection();
    $id = $data['id'];
    unset($data['id']);
    $shipping = new Shipping($con, $id);
    $initial = clone $shipping;
    if ($shipping->id > 0) {
      if ($shipping->status == 'PENDIENTE') {
        if (isset($data['weight'])) { // caso editar final (cuando pone peso y alto ancho)
          $shipping->status = 'EN PROCESO';
          // enviar notificacion
        }
        $shipping->set_data($data);
        if ($shipping->update(null, $initial) > 0) {
          Response::success_json('Actualizado con exito', ['shipping' => $shipping]);
        } else
          Response::error_json(['message' => 'No se encontraron datos para actualizar'], 200);
      } else
        Response::error_json(['message' => 'Error, el shipping ya fue procesado, no es posible editar']);
    } else
      Response::error_json(['message' => 'Error, no se encontro el shipping'], 404);
  }
  public function get_all($query)/* web */ {
    $con = DBWebProvider::getSessionDataDB();

    $status = $query['status'] ?? 'EN PROCESO';
    $shippings = Shipping::get_all($con, ['status' => $status]);
    Render::view('shipping/list_all', ['shippings' => $shippings, 'estado' => $status]);
  }
  public function add_price($body)/*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    if (!Request::required(['id', 'price'], $body))
      Response::error_json(['message' => 'Error, faltan datos'], 200);
    $shipping = new Shipping($con, $body['id']);
    $initial = clone $shipping;
    $shipping->price = $body['price'];
    $shipping->status = 'SIN PAGAR';
    if ($shipping->update(null, $initial) > 0)
      Response::success_json('Actualizado con exito', ['shipping' => $shipping]);
    else
      Response::error_json(['message' => 'Error al actualizar el shipping'], 200);
  }
  public function get_by_id($query)/* web */ {
    $con = DBWebProvider::getSessionDataDB();
    if (!Request::required(['id'], $query))
      Response::error_json(['message' => 'Error, faltan datos'], 200);
    $shipping = new Shipping($con, $query['id']);
    if ($shipping->id > 0) {
      $shipping->load_full_data();
      Render::view('shipping/detail', ['shipping' => $shipping]);
    } else {
      Render::view('error_html', ['message' => 'Error, no se encontro el shipping', 'message_details' => 'el ID enviado no tiene ningun resultado -> ' . $query['id']]);
    }
  }
}
