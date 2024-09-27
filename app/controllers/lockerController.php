<?php

namespace App\Controllers;

use App\Models\Locker;
use App\Models\LockerContent;
use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class LockerController {
  public function list($data) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $lockers = Locker::getAll($con, $data);
    Render::view('locker/list', ['lockers' => $lockers]);
  }
  /**
   * [PUT] Liberar un casillero mediante su ID
   * @return void
   */
  public function change_available($body) /* protected */ {
    $con = DBAppProvider::get_connection();
    $locker = new Locker($con, $body['locker_id']);
    $lockerContent = new LockerContent($con, $body['content_id']);
    $lockerContent->delivered = 1;
    $lockerContent->change_delivered();
    if ($locker->id_locker == 0)
      Response::error_json(['message' => 'Casillero no existente']);
    if ($locker->updateStatus(1))
      Response::success_json('Casillero liberado correctamente', []);
    else
      Response::error_json(['message' => 'Error al liberar casillero']);
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
          $locker->in_out = $data['in_out'];
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
  public function update($data) {
  }
  public function edit_locker($query)/*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    if (isset($query['locker_id']) && !empty($query['locker_id'])) {
      $locker = new Locker($con, $query['locker_id']);
      if ($locker->id_locker == 0) {
        Render::view('error_html', ['message' => 'Casillero no existente', 'message_details' => 'Con el ID enviado no se encontró ningun casillero']);
      } else {
        Render::view('locker/edit_content', ['locker' => $locker]);
      }
    } else {
      Render::view('error_html', ['message' => 'Parametro faltante', 'message_details' => 'No se envió el ID del casillero']);
    }
  }
  /**
   * Controlador donde el conserje agrega contenido a un casillero,
   * @param array $data - datos del contenido a agregar
   * @param mixed $files
   * @return void
   */
  public function add_content($data, $files = null) /* protected conserje*/ {
    if (!Request::required(['user_id', 'locker_id', 'in_out'], $data))
      Response::error_json(['message' => '¡Error!, parámetros faltantes']);
    // in_out ENTRADA SALIDA, shipping_id
    $con = DBAppProvider::get_connection();
    $conserje_id = DBAppProvider::get_payload_value('user_id');
    $user_conserje = new User($con, $conserje_id);
    unset($user_conserje->password);
    $locker = new Locker($con, $data['locker_id']);
    if ($locker->id_locker != 0) {
      $resident = new Resident($con, $data['user_id']);
      if ($resident->id_user != 0) {
        $received_by = DBAppProvider::get_payload_value('user_id');
        $content = $locker->addContent($data['user_id'], $data['content'] ?? '', $resident->department_id, $received_by);
        if ($content->id_content > 0) {
          if ($data['in_out'] == 'SALIDA') { // Necesario shipping_id
            if (!Request::required(['shipping_id'], $data))
              Response::error_json(['message' => '¡Error!, parámetros faltantes SHIPPING ID']);
            $content->shipping_id = $data['shipping_id'];
            $content->set_shipping_id($data['shipping_id']);
          }
        } else
          Response::error_json(['message' => 'Error al agregar contenido al casillero']);
        if ($resident->subscription_valid()) { // enviar notificacion solo si esta suscrito
          $message = $locker->message_notification();
          $buttons = [
            ['id' => 'call-janitor', 'text' => 'Enviar Mensaje'],
          ];
          $resident->department();
          $user_conserje->{'locker_number'} = $locker->locker_number;
          $user_conserje->{'dep_number'} = $resident->department->dep_number;
          $user_conserje->{'content'} = $data['content'];
          $res_noti = Notification::send_id($resident->device_id, $message, "TeLoPago", $user_conserje, $buttons);
          if (!isset($res_noti['errors']))
            Response::success_json('Guardado y notificación enviada correctamente', ['notification' => $res_noti], 200);
          else
            Response::success_json('Guardado, notificación no enviada ' . json_encode($res_noti['errors']), [], 200);
        } else
          Response::success_json('Guardado, residente no suscrito', [], 200);
      } else
        Response::error_json(['message' => 'Residente no encontrado'], 404);
    } else
      Response::error_json(['message' => 'Casillero no encontrado'], 404);
  }
  public function list_all($query) /* protected */ {
    $con = DBAppProvider::get_connection();
    $in_out = $query['in_out'] ?? 'ENTRADA';
    if ($con) {
      $lockers = Locker::getAll($con, []);
      Response::success_json('Casilleros', $lockers);
    } else
      Response::error_json(['message' => 'Error en conexión de instancia'], 500);
  }
  public function list_content($query)/* protected */ {
    $con = DBAppProvider::get_connection();
    $subscription = DBAppProvider::get_sub();
    $bandeja = $query['in_out'] ?? 'ENTRADA';
    $content_history = LockerContent::get_list_department($con, $subscription->department_id, $bandeja);
    $conserje = null;
    if (count($content_history) > 0) {
      $last = $content_history[0];
      $conserje = new User($con, $last['received_by']);
      unset($conserje->password);
      unset($conserje->device_id);
    }
    Response::success_json("Contenido de casilleros $bandeja", ['conserje' => $conserje, 'content' => $content_history]);
  }
  public function change_delivered($body) /*protected*/ {
    $con = DBAppProvider::get_connection();
    if (!Request::required(['content_id'], $body))
      Response::error_json(['message' => 'Datos incompletos']);

    $content = new LockerContent($con, $body['content_id']);
    if ($content->id_content == 0) {
      Response::error_json(['message' => 'Contenido no encontrado']);
    } else {
      $content->delivered = 1;
      if ($content->change_delivered())
        Response::success_json('Contenido actualizado correctamente', []);
      else
        Response::error_json(['message' => 'Error al actualizar contenido'], 200);
    }
  }
  public function history($query)/*protected*/ {
    if (!isset($query['locker_id']))
      Response::error_json(['message' => 'Datos incompletos']);
    $con = DBAppProvider::get_connection();
    $content_history = LockerContent::get_history_locker($con, $query['locker_id']);
    Response::success_json('Historial de casillero', $content_history);
  }
  public function get_last_content($query)/* protected */ {
    if (!isset($query['locker_id']))
      Response::error_json(['message' => 'Datos incompletos LOCKER ID']);
    $con = DBAppProvider::get_connection();
    $content = LockerContent::last($con, $query['locker_id']);
    Response::success_json('Último contenido del casillero', ['content_info' => $content]);
  }
  public function history_last($query)/*protected*/ {
    $con = DBAppProvider::get_connection();
    $where = "WHERE a.delivered != 1";
    $order = "ORDER BY a.received_at DESC";
    $content_history = LockerContent::content_with_content($con, $where, $order);
    Response::success_json('Historial de contenido', $content_history);
  }
}
