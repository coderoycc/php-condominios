<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Department;
use App\Models\ServiceDetail;
use App\Models\Services;
use App\Models\Subscription;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

use function App\Services\event;
use function App\Utils\url_public_condominio;

class ServicesController {
  static $months = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
  public function add_code_service($body, $files = null) /* protected */ {
    if (!Request::required(['service_name', 'code', 'id_sername'], $body))
      Response::error_json(['message' => 'Campos faltantes [service_name, code, id_sername]']);

    $subscription = DBAppProvider::get_sub();
    $subscription->type();
    if ($subscription->type->see_services) {
      $con = DBAppProvider::get_connection();
      $pin = DBAppProvider::get_payload_value('pin');
      $department = new Department($con, $subscription->department_id);
      $resident = DBAppProvider::get_resident();
      $code = $body['code'];
      if (Services::exist_code($con, $code, $subscription->id_subscription))
        Response::error_json(['message' => 'El código ya existe'], 400);

      $service = new Services($con);
      $service->service_name = $body['service_name'];
      $service->code = $code;
      $service->user_id = $resident->id_user;
      $service->subscription_id = $subscription->id_subscription;
      $service->service_name_id = $body['id_sername'];
      if ($service->save() > 0) {
        $event = event()->new('Nuevo servicio registrado', 'Se ha registrado servicio ' . $body['service_name'] . ' para el departamento ' . $department->dep_number, $pin, 'services', 'info');
        event()->notify($event);
        Response::success_json('Success Request', ['service' => $service]);
      } else
        Response::error_json(['message' => 'Error al crear servicio'], 200);
    } else {
      Response::error_json((['message' => 'Su suscripción no le permite agregar servicios']), 200);
    }
  }
  public function get_my_services($query) /*protected*/ {
    $con = DBAppProvider::get_connection();
    $sub = DBAppProvider::get_sub();
    if ($sub->id_subscription > 0) {
      $services = Services::list_by_subscription($con, $sub->id_subscription);
      Response::success_json('Success Request', ['services' => $services]);
    } else {
      Response::error_json((['message' => '¡Error! Residente no asociado a un departamento']), 200);
    }
  }
  public function update_my_service($body) /*protected*/ {
    if (!Request::required(['code', 'id'], $body))
      Response::error_json(['message' => 'Campos requeridos [code, id]']);

    $con = DBAppProvider::get_connection();
    $service = new Services($con, $body['id']);
    $newService = clone $service;
    $subscription = DBAppProvider::get_sub();
    if ($service->id_service > 0) {
      $code = $body['code'];
      if (Services::exist_code($con, $code, $subscription->department_id))
        Response::error_json(['message' => 'No puede agregar un código existente'], 400);

      $newService->code = $code;
      if ($newService->update(null, $service) > 0)
        Response::success_json('Success Request', ['service' => $newService]);
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
    $subscriptions = Services::subscriptions_enable_to_services();
    Render::view('services/subs_enable', ['subscriptions' => $subscriptions]);
  }
  public function history_all($query)/*web*/ {
    $year = $query['year'] ?? date('Y');
    $services = Services::list_filters_all('PAGADO', $year); // todos los pagados

    Render::view('services/list_history', ['services' => $services, 'months' => self::$months, 'year' => $year]);
  }
  public function codes_department($query) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    $year = $query['year'] ?? date('Y');
    $year = intval($year);
    $months = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    $subscription = new Subscription($con, $query['id']);
    $department = new Department($con, $subscription->department_id);
    $sums = Services::sum_by_subscription($con, $subscription->id_subscription, $year);
    Render::view('services/codes_list', ['department' => $department, 'sums' => $sums, 'months' => $months, 'year' => $year]);
  }
  /**
   * Suma general de todos los servicios de una suscripcion de un año
   * @param array $query
   * @return void
   */
  public function codes_department_app($query)/* Protected */ {
    $con = DBAppProvider::get_connection();
    $subscription = DBAppProvider::get_sub();
    $year = $query['year'] ?? date('Y');
    $year = intval($year);
    if ($subscription->id_subscription > 0) {
      $codes_for_month = Services::sum_by_subscription($con, $subscription->id_subscription, $year);
      Response::success_json('Success Request', ["global" => $codes_for_month]);
    } else
      Response::error_json(['message' => 'Error al obtener suscripcion'], 500);
  }
  public function detail_services_month($query)/*protected */ {
    if (!Request::required(['month', 'year'], $query))
      Response::error_json(['message' => 'Campos faltantes MES y AÑO']);
    $con = DBAppProvider::get_connection();
    $month = intval($query['month']);
    $year = intval($query['year']);
    $subscr = DBAppProvider::get_sub();

    $resp = Services::detail_for_month($con, $month, $year, $subscr->id_subscription);
    Response::success_json('Success Request', ["detail" => $resp]);
  }
  public function fill_amounts($query) /*web global*/ {
    if (!Request::required(['key'], $query))
      Render::view('error_html', ['message' => 'No se ha proporcionado la llave de acceso', 'message_detail' => 'Requerido la llave del condominio PIN']);
    $key = $query['key'];
    $con = Database::getInstanceByPin($key);
    $subscription = new Subscription($con, $query['id']);
    $department = new Department($con, $subscription->department_id);
    $services = Services::list_by_subscription($con, $query['id']);
    Render::view('services/fill_amounts', ['services' => $services, 'department' => $department, 'nuevo' => true, 'subscription' => $subscription, 'key' => $key]);
  }
  public function edit_amounts($query) /*web global*/ {
    if (!Request::required(['key', 'year', 'month', 'sub_id'], $query))
      Render::view('error_html', ['message' => 'No se ha proporcionado la llave de acceso', 'message_detail' => 'Requerido la llave del condominio PIN']);

    $key = $query['key'];
    $con = Database::getInstanceByPin($key);
    $month = $query['month'] > 9 ? $query['month'] : "0" . $query['month'];
    $year = $query['year'];
    $subscription = new Subscription($con, $query['sub_id']);
    $department = new Department($con, $subscription->id_subscription);
    $services = ServiceDetail::list_bysub_amounts($con, $year, $month, $subscription->id_subscription);
    Render::view('services/fill_amounts', ['services' => $services, 'department' => $department, 'nuevo' => false, 'year' => $year, 'month' => $month, 'subscription' => $subscription, 'key' => $key]);
  }
  public function add_amounts($body) /*web global*/ {
    if (!Request::required(['key', 'sub_id', 'year', 'month'], $body))
      Response::error_json(['message' => 'Campos faltantes'], 200);

    $key = $body['key'];
    $ids = $body['ids'];
    $amounts = $body['amounts'];
    $con = Database::getInstanceByPin($key);
    $mes = $body['month'];
    $year = $body['year'];
    $sub_id = $body['sub_id'];
    $n = count($ids);
    $exist = ServiceDetail::verify_exist($con, $mes, $year, $sub_id);
    if ($exist) {
      Response::error_json(['message' => 'Los montos para este mes ya fueron registrados'], 200);
    }
    $response = true;
    for ($i = 0; $i < $n; $i++) {
      $detail = new ServiceDetail($con);
      $detail->service_id = intval($ids[$i]);
      $detail->amount = $amounts[$i];
      $detail->month = $mes;
      $detail->year = $year;
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
  public function update_amounts($body) /* web global */ {
    if (!Request::required(['key'], $body))
      Response::error_json(['message' => 'Requerido llave de acceso'], 200);

    $key = $body['key'];
    $con = Database::getInstanceByPin($key);
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
  public function services_in_process($query) {
    $year = $query['year'] ?? date('Y');
    $services = Services::list_filters_all('SIN PAGO', $year); // vacio SIN PAGO
    Render::view('services/list_global_services', ['services' => $services, 'months' => self::$months, 'year' => $year]);
  }
  public function services_to_pay($query) /*web global*/ {
    $year = $query['year'] ?? date('Y');
    $services = Services::list_filters_all('QR PAGADO', $year); // pagados por el residente
    Render::view('services/list_to_pay', ['services' => $services, 'months' => self::$months, 'year' => $year]);
  }
  public function detail_payment($query)/*web global*/ {
    if (!Request::required(['id', 'month', 'year', 'key'], $query))
      Render::view('error_html', ['message' => 'Campos faltantes', 'message_deatail' => 'Parametros requeridos no identificados.']);

    $month = self::$months[intval($query['month'])];
    $year = $query['year'];
    $key = $query['key'];
    $con = Database::getInstanceByPin($key);

    $subscription = new Subscription($con, $query['id']);
    $department = new Department($con, $subscription->id_subscription);
    $services = ServiceDetail::list_bysub_amounts($con, $year, $query['month'], $subscription->id_subscription);

    $url_name = url_public_condominio($key, 'vouchers');


    Render::view('services/detail', ['department' => $department, 'services' => $services, 'month' => $month, 'year' => $year, 'key' => $key, 'urlbase' => $url_name]);
  }
  public function pay_voucher($query) {
    if (!Request::required(['id', 'month', 'year', 'key'], $query))
      Render::view('error_html', ['message' => 'Campos faltantes', 'message_deatail' => 'Parametros requeridos no identificados.']);

    $month = self::$months[intval($query['month'])];
    $year = $query['year'];
    $key = $query['key'];
    $con = Database::getInstanceByPin($key);

    $subscription = new Subscription($con, $query['id']);
    $department = new Department($con, $subscription->id_subscription);
    $services = ServiceDetail::list_bysub_amounts($con, $year, $query['month'], $subscription->id_subscription);

    Render::view('services/form_to_pay', ['department' => $department, 'services' => $services, 'month' => $month, 'year' => $year, 'key' => $key, 'subscription' => $subscription]);
  }
  public function add_vouchers_payment($body, $files) {
    if (!Request::required(['key', 'id'], $body))
      Response::error_json(['message' => 'Campos faltantes'], 200);

    $key = $body['key'];
    $con = Database::getInstanceByPin($key);

    $subscription = new Subscription($con, $body['id']);
    var_dump($files['files']['name']);
    var_dump($files['files']['tmp_name']);
    var_dump($body['ids'][0]);
  }
}
