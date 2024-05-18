<?php

namespace App\Controllers;

use App\Models\Locker;
use App\Models\Notification;
use App\Models\Resident;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class LockerController {
  public function list($data) {
    $con = DBWebProvider::getSessionDataDB();
    $lockers = Locker::getAll($con, $data);
    Render::view('locker/list', ['lockers' => $lockers]);
  }
  public function create($data, $file = null) {
    if (DBWebProvider::session_exists()) {
      $con = DBWebProvider::getSessionDataDB();
      if ($con) {
        if (!Locker::number_exist($con, $data['nro'])) {
          $locker = new Locker($con);
          $locker->locker_status = $data['status'] ?? 1;
          $locker->locker_number = $data['nro'];
          $locker->type = $data['detail'];
          if ($locker->save() > 0) {
            Response::success_json('Casillero creado correctamente', ['locker' => $locker]);
          } else {
            Response::error_json(['message' => '[Locker ctrl] Error al crear casillero']);
          }
        } else Response::error_json(['message' => 'Número de casillero ya existente']);
      } else
        Response::error_json(['message' => '[Locker ctrl] Error en conexión de instancia']);
    } else {
      Response::error_json(['message' => '[Locker ctrl] Sesión no iniciada']);
    }
  }
  public function delete($data) {
    if (!Request::required(['id'], $data))
      Response::error_json(['message' => '[Locker ctrl] Datos incompletos']);
    $con = DBWebProvider::getSessionDataDB();
    $locker = new Locker($con, $data['id']);
    if ($locker->id_locker) {
      if ($locker->delete())
        Response::success_json('Casillero eliminado correctamente', []);
      else
        Response::error_json(['message' => 'Error al eliminar casillero']);
    } else
      Response::error_json(['message' => 'Casillero no existente']);
  }

  public function add_content($data, $files = null) /* protected */ {
    if (!Request::required(['user_id', 'locker_id'], $data))
      Response::error_json(['message' => '¡Error!, parámetros faltantes']);

    $con = DBAppProvider::get_conecction();
    $locker = new Locker($con, $data['locker_id']);
    if ($locker->id_locker != 0) {
      $resident = new Resident($con, $data['user_id']);
      if ($resident->id_user != 0) {
        $locker->addContent($data['user_id'], $data['content'] ?? '');
        if ($resident->subscription_valid()) { // enviar notificacion solo si esta suscrito
          $message = $locker->type == "todo" ?
            'Usted acaba de recibir un pedido en el casillero Nro. ' . $locker->locker_number . '. Tiene 30 min. para recogerlo.' :
            'Usted acaba de recibir correspondencia en el casillero Nro. ' . $locker->locker_number;
          $res_noti = Notification::send_id($resident->device_id, $message, "TeLoPago");
          if (!isset($res_noti['errors'])) {
            Response::success_json('Guardado y notificación enviada correctamente', ['notification' => $res_noti], 200);
          } else {
            Response::success_json('Guardado, notificación no enviada', [], 200);
          }
        } else {
          Response::success_json('Guardado, residente no suscrito', [], 200);
        }
      } else
        Response::error_json(['message' => 'Residente no encontrado'], 404);
    } else
      Response::error_json(['message' => 'Casillero no encontrado'], 404);
  }
  public function list_all($query) /* protected */ {
    $con = DBAppProvider::get_conecction();
    if ($con) {
      $lockers = Locker::getAll($con, []);
      Response::success_json('Casilleros', $lockers);
    } else
      Response::error_json(['message' => 'Error en conexión de instancia'], 500);
  }
}
