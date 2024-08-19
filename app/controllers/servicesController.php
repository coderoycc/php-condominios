<?php

namespace App\Controllers;

use App\Models\Department;
use App\Models\ServiceDetail;
use App\Models\Services;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class ServicesController {
  public function add_code_service($body, $files = null) /* protected */ {
    if (!Request::required(['service_name', 'code', 'id_sername'], $body))
      Response::error_json(['message' => 'Campos faltantes [service_name, code, id_sername]']);

    $subscription = DBAppProvider::get_sub();
    $subscription->type();
    if ($subscription->type->see_services) {
      $con = DBAppProvider::get_connection();
      $resident = DBAppProvider::get_resident();
      $code = $body['code'];
      if (Services::exist_code($con, $code, $subscription->department_id))
        Response::error_json(['message' => 'El código ya existe'], 400);

      $service = new Services($con);
      $service->service_name = $body['service_name'];
      $service->code = $code;
      $service->user_id = $resident->id_user;
      $service->department_id = $subscription->department_id;
      $service->service_name_id = $body['id_sername'];
      if ($service->save() > 0)
        Response::success_json('Success Request', ['service' => $service]);
      else
        Response::error_json(['message' => 'Error al crear servicio'], 200);
    } else {
      Response::error_json((['message' => 'Su suscripción no le permite agregar servicios']), 200);
    }
  }
  public function get_my_services($query) /*protected*/ {
    $con = DBAppProvider::get_connection();
    $sub = DBAppProvider::get_sub();
    if ($sub->id_subscription > 0) {
      $services = Services::list_by_department($con, $sub->department_id);
      Response::success_json('Success Request', ['services' => $services]);
    } else {
      Response::error_json((['message' => '¡Error! Residente no asociado a un departamento']), 200);
    }
  }
  public function update_my_service($body) /*protected*/ {
    if (!Request::required(['code', 'id'], $body)) {
      Response::error_json(['message' => 'Campos requeridos [code, id]']);
    }
    $con = DBAppProvider::get_connection();
    $service = new Services($con, $body['id']);
    $subscription = DBAppProvider::get_sub();
    if ($service->id_service > 0) {
      $code = $body['code'];
      if (Services::exist_code($con, $code, $subscription->department_id))
        Response::error_json(['message' => 'No puede agregar un código existente'], 400);

      $service->code = $code;
      if ($service->save() > 0)
        Response::success_json('Success Request', ['service' => $service]);
      else
        Response::error_json(['message' => 'Error al actualizar servicio'], 500);
    } else {
      Response::error_json(['message' => 'Servicio no encontrado'], 404);
    }
  }
  public function delete_my_service($body) /*protected*/ {
    if (!Request::required(['id'], $body))
      Response::error_json(['message' => 'Campos faltantes']);
    $con = DBAppProvider::get_connection();
    $service = new Services($con, $body['id']);
    if ($service->id_service > 0) {
      $res = $service->delete();
      if ($res['status'])
        Response::success_json($res['message'] ?? 'OK', ['service' => $service]);
      else
        Response::error_json(['message' => $res['message']], 200);
    } else {
      Response::error_json(['message' => 'Servicio no encontrado por su ID'], 404);
    }
  }
  public function list_subs($query)/* web */ {
    $con = DBWebProvider::getSessionDataDB();
    $services = Services::subs_all($con, $query);
    Render::view('services/list_services', ['services' => $services]);
  }
  public function codes_department($query) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $year = $query['year'] ?? date('Y');
    $year = intval($year);
    $months = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    $department = new Department($con, $query['id']);
    $sums = Services::sum_department($con, $department->id_department, $year);
    Render::view('services/codes_list', ['department' => $department, 'sums' => $sums, 'months' => $months, 'year' => $year]);
  }
  public function codes_department_app($query)/* Protected */ {
    $con = DBAppProvider::get_connection();
    $subscription = DBAppProvider::get_sub();
    $year = $query['year'] ?? date('Y');
    $year = intval($year);
    if ($subscription->id_subscription > 0) {
      $department = new Department($con, $subscription->department_id);
      $codes_for_month = Services::sum_department($con, $department->id_department, $year);
      Response::success_json('Success Request', ["global" => $codes_for_month]);
    } else
      Response::error_json(['message' => 'Error al obtener suscripcion'], 500);
  }
  public function detail_services_month($query)/*protected */ {
    if (!Request::required(['month', 'year'], $query))
      Response::error_json(['message' => 'Campos faltantes']);
    $con = DBAppProvider::get_connection();
    $month = intval($query['month']);
    $year = intval($query['year']);
    $subscr = DBAppProvider::get_sub();

    $resp = Services::detail_for_month($con, $month, $year, $subscr->department_id);
    Response::success_json('Success Request', ["detail" => $resp]);
  }
  public function fill_amounts($query) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $department = new Department($con, $query['id']);
    $services = Services::list_by_department($con, $query['id']);
    Render::view('services/fill_amounts', ['services' => $services, 'department' => $department, 'nuevo' => true]);
  }
  public function edit_amounts($query) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $month = $query['month'];
    $year = $query['year'];
    $fecha = $year . '-' . ($month > 9 ? $month : '0' . $month) . '-01';
    $department = new Department($con, $query['depa_id']);
    $services = ServiceDetail::list_depa_amounts($con, $fecha, $query['depa_id']);
    Render::view('services/fill_amounts', ['services' => $services, 'department' => $department, 'nuevo' => false, 'fecha' => $fecha]);
  }
  public function add_amounts($body) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $ids = $body['ids'];
    $amounts = $body['amounts'];
    $department_id = $body['id_department'];
    $mes = $body['mes'] . '-01';
    $n = count($ids);
    $exist = ServiceDetail::verify_exist($con, intval(explode('-', $mes)[1]), intval(explode('-', $mes)[0]), $department_id);
    if ($exist) {
      Response::error_json(['message' => 'Los montos para este mes ya fueron registrados'], 200);
    }
    $response = true;
    for ($i = 0; $i < $n; $i++) {
      $detail = new ServiceDetail($con);
      $detail->service_id = intval($ids[$i]);
      $detail->amount = $amounts[$i];
      $detail->month = $mes;
      if ($detail->insert() <= 0) {
        $response = false;
        break;
      }
    }
    if ($response)
      Response::success_json('Agregado correctamente', ['OK' => 'Servicios agregados']);
    else
      Response::error_json(['message' => 'Error al agregar servicios'], 200);
  }
  public function update_amounts($body) /* web */ {
    $con = DBWebProvider::getSessionDataDB();
    $detail_ids = $body['id_detail'];
    $amounts = $body['amounts'];
    $n = count($detail_ids);
    $updateds = 0;
    for ($i = 0; $i < $n; $i++) {
      $detail = new ServiceDetail($con, $detail_ids[$i]);
      $detail->amount = $amounts[$i];
      if ($detail->update() > 0) $updateds++;
    }
    Response::success_json('Actualizado correctamente', ['affected' => $updateds, 'total' => $n]);
  }
  public function my_service_balance() {
  }
}
