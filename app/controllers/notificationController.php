<?php

namespace App\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Providers\DBAppProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class NotificationController {
  public function send_by_id($data, $file = null) /*protected*/ {
    if (!Request::required(['id', 'nro_casillero'], $data))
      Response::error_json(['message' => 'Parámetros faltantes [id, nro_casillero]']);

    $con = DBAppProvider::get_connection();
    if ($con) {
      $id = $data['id'];
      $user = new User($con, $id);
      if ($user->id_user > 0) {
        $nro_casillero = $data['nro_casillero'];
        $mensaje = $nro_casillero == 1 ? 'Usted acaba de recibir correspondencia en el casillero Nro. ' . $nro_casillero :
          'Usted acaba de recibir un pedido en el casillero Nro. ' . $nro_casillero . '. Tiene 30 min. para recogerlo.';
        $data_send = [];
        if ($user->device_id != '00-00-000') {
          $response = Notification::send_id($user->device_id, $mensaje, 'TeLoPagoCasillero', $data_send);
          if (isset($response['errors'])) {
            Response::error_json(['message' => 'Error al enviar notificación'], 200);
          } else {
            Response::success_json('Notificación enviada correctamente', $response, 200);
          }
        }
      } else {
        Response::error_json(['message' => 'No se encontró el usuario'], 200);
      }
    } else {
      Response::error_json(['message' => 'Error instancia de conexión, token no valido'], 200);
    }
  }
}
