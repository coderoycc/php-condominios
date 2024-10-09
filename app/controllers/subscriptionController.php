<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Providers\DBWebProvider;
use Helpers\Resources\HandleDates;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class SubscriptionController {
  public function types($data) {
    if (!Request::required(['pin'], $data))
      Response::error_json(['message' => 'Datos faltantes: [pin] requerido']);
    $data = Subscription::getTypes($data['pin']);
    $response = [];
    foreach ($data as $type) {
      $subType = new Subscriptiontype();
      $subType->load($type);
      $response[] = $subType;
    }
    Response::success_json('Tipos de suscripción', $response);
  }
  public function types_web($data) /*web*/ {
    $condominio = DBWebProvider::session_get_condominio();
    if (isset($condominio->pin)) {
      $data_subs = Subscription::getTypes($condominio->pin);
      Render::view('subscription/type_list', ['data_subs' => $data_subs]);
    } else {
      Render::view('error_html', ['message' => 'PIN no encontrado', 'message_detail' => 'Inicie sesión']);
    }
  }
  public function subscribe($data, $files = null) {
    if (!Request::required(['type_id', 'user_id', 'pin', 'annual'], $data))
      Response::error_json(['message' => 'Parametros faltantes']);

    $con = Database::getInstanceByPin($data['pin']);
    $type = new Subscriptiontype($con, $data['type_id']);
    $resident = new Resident($con, $data['user_id']);
    // existe departamento con suscripcion
    $subsDepa = Subscription::get_department_subscription($con, $resident->department_id, ['status' => 'VALIDO', 'no_expired' => true]);
    if (count($subsDepa)) {
      Response::error_json(['success' => false, 'message' => 'El departamento ya tiene una suscripción', 'error' => true], 200);
    }
    if ($type->price > 0) {
      $precio = $data['annual'] ? $type->annual_price : $type->price;
      // $precio = $periods * $type->price;
      $payment = new Payment($con, null);
      $payment->amount = $precio;
      $payment->app_user_id = $data['user_id'];
      $payment->gloss = 'Pago suscripcion ' . $type->name;
      $qrImage = $payment->pay_with_qr();
      if ($payment->id_qr > 0) {
        Response::success_json('QR Generado', ['payment' => $payment, 'qr' => $qrImage]);
      } else {
        Response::error_json(['message' => 'Error al generar QR', 'error' => true]);
      }
    } else { // gratuito
      if (Subscription::verify_subscription_free($con, $type->id, $resident)) {
        $subscription = new Subscription($con, null);
        $subscription->type_id = $type->id;
        $subscription->paid_by = $data['user_id'];
        $subscription->paid_by_name = $resident->first_name;
        $subscription->period = 0;
        $subscription->nit = '000';
        $subscription->department_id = $resident->department_id;
        $subscription->expires_in = HandleDates::date_expire_month(1);
        $subscription->status = 'VALIDO';
        $subscription->code = $subscription->genCode();
        $subscription->limit = 1;
        if ($subscription->insert() > 0) {
          Response::success_json('Suscripción realizada', ['subscription' => $subscription]);
        } else {
          Response::error_json(['message' => 'Error al suscribirse', 'error' => true], 200);
        }
      } else {
        Response::error_json(['message' => 'Limite máximo de suscripciones gratuitas para el departamento', 'error' => true], 200);
      }
    }
  }
  public function report_all($data) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $start = HandleDates::date_format_db($data['fecha_incio'] ?? null);
      $end = HandleDates::date_format_db($data['fecha_final'] ?? null);
      $suscripciones = Subscription::get_subscriptions_all($con, ['start' => $start, 'end' => $end]);
      $total = intval(count($suscripciones));
      $types = [];
      foreach ($suscripciones as $sub) {
        if (isset($types[$sub['name']])) {
          $types[$sub['name']]['totalAmount'] += $sub['amount'] ?? 0;
          $types[$sub['name']]['count'] += $sub['period'] == 0 ? 1 : intval($sub['period']); // son 0 en plan free
        } else {
          $types[$sub['name']] = ['totalAmount' => $sub['amount'] ?? 0, 'count' => $sub['period'] == 0 ? 1 : intval($sub['period']), 'price' => $sub['price']];
        }
      }
      Render::view('reports/all_subs', ['total' => $total, 'types' => $types]);
    }
  }
  public function report_by($data) {
    $con = DBWebProvider::getSessionDataDB();
    if (!Request::required(['id'], $data)) {
      Render::view('error_html', ['message' => 'Parámetros faltantes <b>[ID]</b>', 'message_details' => 'Comunique al administrador e inténtelo más tarde']);
    }
    if ($con) {
      $id = $data['id'];
      $start = HandleDates::date_format_db($data['fecha_incio'] ?? null);
      $end = HandleDates::date_format_db($data['fecha_final'] ?? null);
      $suscripciones = Subscription::get_subscriptions_by_typeId($con, $id, ['start' => $start, 'end' => $end]);
      $qty = count($suscripciones);
      $estados = ['vencidos' => 0, 'vigentes' => 0];
      $months_subs = [];
      foreach ($suscripciones as $sub) {
        $keyMonth = HandleDates::get_month_str($sub['subscribed_in']);
        if (isset($months_subs[$keyMonth])) {
          $months_subs[$keyMonth]['amount'] += $sub['amount'] ?? 0;
          $months_subs[$keyMonth]['count']++;
        } else {
          $months_subs[$keyMonth] = ['amount' => $sub['amount'] ?? 0, 'count' => 1];
        }
        if (HandleDates::expired($sub['expires_in'])) {
          $estados['vencidos']++;
        } else {
          $estados['vigentes']++;
        }
      }
      Render::view('reports/subs_by_id', ['estados' => $estados, 'months_subs' => $months_subs, 'qty' => $qty, 'id' => $id, 'start' => $start, 'end' => $end]);
    } else {
      Render::view('error_html', ['message' => 'Error instancia de conexión', 'message_details' => 'Comunique al administrador e inténtelo más tarde']);
    }
  }
  public function add_type($data, $files = null) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $type = new Subscriptiontype($con, null);
      $type->name = $data['tag'];
      $type->price = $data['precio'];
      $type->description = $data['descrip'];
      $type->see_lockers = $data['verCasillero'];
      $type->see_services = $data['verServicio'];
      $type->months_duration = 6;
      if ($type->save()) {
        Response::success_json('Tipo de suscripción agregado', ['type' => $type]);
      } else {
        Response::error_json(['message' => 'Error al guardar tipo de suscripción', 'error' => true], 200);
      }
    } else {
      Response::error_json(['message' => 'Error instancia de conexión', 'error' => true], 200);
    }
  }
  public function delete_type($data) {
    $con = DBWebProvider::getSessionDataDB();
    if (!Request::required(['id'], $data)) {
      Response::error_json(['message' => 'Parámetros faltantes', 'error' => true], 200);
    }

    if ($con) {
      $exist = Subscriptiontype::dependency_exist($con, $data['id']);
      if ($exist)
        Response::error_json(['message' => 'Existen usuarios con esta suscripción', 'error' => true], 200);
      else {
        $type = new Subscriptiontype($con, $data['id']);
        if ($type->id > 0) {
          if ($type->delete())
            Response::success_json('Tipo de suscripción eliminado', [], 200);
          else
            Response::error_json(['message' => 'Error al eliminar tipo de suscripción', 'error' => true], 200);
        } else
          Response::error_json(['message' => 'No existe ese tipo de suscripción', 'error' => true], 200);
      }
    } else
      Response::error_json(['message' => 'Error instancia de conexión', 'error' => true], 200);
  }
  public function join_with_code($data, $files = null) {
    if (!Request::required(['pin', 'code', 'user_id'], $data))
      Response::error_json(['message' => 'Datos faltantes', 'error' => true], 400);

    $con = Database::getInstanceByPin($data['pin']);
    $resident = new Resident($con, $data['user_id']);
    if ($resident->id_user > 0) {
      $depa_id = $resident->department_id;
      $code = $data['code'];
      $code_subs = Subscription::getSubscriptionByCode($con, $code, $depa_id);
      if (!$code_subs['valid']) {
        Response::error_json(['message' => 'Código de suscripción no válido']);
      } else if ($code_subs['limit_reached']) {
        Response::error_json(['message' => 'Limite alcanzado para el código de suscripción']);
      } else if (!$code_subs['depa_ok']) {
        Response::error_json(['message' => "Este código $code, no pertenece a su departamento"]);
      }

      $r = Subscription::addUserSubscription($con, $data['user_id'], $code_subs['subs_id']);
      if ($r)
        Response::success_json('Suscripción realizada', []);
      else
        Response::error_json(['message' => 'Error al suscribir usuario']);
    } else
      Response::error_json(['message' => 'Usuario no encontrado', 'error' => true], 404);
  }
  public function get($query)/*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $subscription = new Subscription($con, $query['id']);
      if ($subscription->id_subscription > 0) {
        $subscription->type();
        Render::view('subscription/details', ['subscription' => $subscription]);
      } else {
        Render::view('error_html', ['message' => 'No existe la suscripción']);
      }
    } else
      Render::view('error_html', ['message' => 'Error instancia de conexión', 'message_details' => 'Comunique al administrador e inténtelo más tarde']);
  }
  public function change_plan($body) {
    if (!Request::required(['idsub', 'key'], $body))
      Response::error_json(['message' => 'Datos faltantes'], 400);

    $con = Database::getInstanceByPin($body['key']);
    $subscription = new Subscription($con, $body['idsub']);
    if ($subscription->id_subscription) {
      $subscription->type_id = $body['type'];
      $subscription->subscribed_in = HandleDates::date_format_db(date('Y-m-d H:i:s'));
      $subscription->expires_in = HandleDates::date_expire_month(3);
      if ($subscription->change_plan() > 0) {
        Response::success_json('Cambio de plan realizado', ['subscription' => $subscription]);
      } else
        Response::error_json(['message' => 'Error al cambiar plan']);
    } else
      Response::error_json(['message' => 'No existe la suscripción']);
  }
  public function get_new_subscription($query)/*web*/ {
    $con = Database::getInstanceByPin($query['pin']);
    $resident = new Resident($con, $query['user_id']);
    if ($resident->department_id > 0) {
      $resident->department();
      $types = Subscriptiontype::getTypes(null, $con);
      Render::view('subscription/edit_content', ['types' => $types, 'resident' => $resident, 'pin' => $query['pin']]);
    } else {
      Render::view('error_html', ['message' => 'Proceso cancelado', 'message_details' => 'El usuario no tiene un departamento asociado']);
    }
  }
  public function add_subscription($body, $files = null)/*web*/ {
    $pin = $body['key'] ?? null;
    if ($pin)  //desde global
      $con = Database::getInstanceByPin($pin);
    else { // en web desde Seccion WEB 
      $con = DBWebProvider::getSessionDataDB();
      if (!$con)
        Response::error_json(['message' => 'Error instancia de conexión', 'message_details' => 'Comunique al administrador e inténtelo más tarde']);
    }
    $type = new Subscriptiontype($con, $body['type']);

    // $precio = $body['btnradio'] == 12 ? $type->annual_price : $type->price;
    $precio = $body['price'];

    $payment = new Payment($con, null);
    $payment->amount = $precio;
    $payment->app_user_id = $body['paid_by'];
    $payment->gloss = 'Pago suscripcion ' . $type->name;
    $payment->pay_with_qr();
    if ($payment->id_qr > 0) {
      $subscription = new Subscription($con, null);
      $subscription->type_id = $type->id;
      $subscription->paid_by = $body['paid_by'];
      $subscription->paid_by_name = $body['razon_social'];
      $subscription->period = 0;
      $subscription->nit = $body['nit'] !== '' ? $body['nit'] : '000';
      $subscription->department_id = $body['depa_id'];
      $subscription->expires_in = HandleDates::date_expire_month($body['btnradio']);
      $subscription->status = 'VALIDO';
      $subscription->code = $subscription->genCode();
      $subscription->limit = 3;
      if ($subscription->insert() > 0) {
        Payment::relation_payment_subscription($con, $payment->idPayment, $subscription->id_subscription);
        Response::success_json('Suscripción realizada', ['subscription' => $subscription]);
      } else {
        Response::error_json(['message' => 'Error al suscribirse', 'error' => true], 200);
      }
    } else {
      Response::error_json(['message' => 'No se pudo crear la suscripcion', 'error' => true]);
    }
  }
  public function history_sub_department($query)/*web*/ {
    if ($query['department_id']) {
      $con = DBWebProvider::getSessionDataDB();
      $subscriptions = Subscription::get_subscriptions_by_department($con, $query['department_id']);
      Render::view('subscription/history_sub_department', ['subscriptions' => $subscriptions, 'department_id' => $query['department_id']]);
    } else
      Render::view('error_html', ['message' => 'No existe el departamento', 'message_details' => 'Parametros faltantes']);
  }
  public function suspend($body)/*web*/ {
    if (!Request::required(['key', 'sub_id'], $body))
      Response::error_json(['message' => 'Parametros necesarios no enviados', 'data' => []], 200);

    $con = Database::getInstanceByPin($body['key']);
    $subscription = new Subscription($con, $body['sub_id']);

    if ($subscription->id_subscription) {
      $subscription->status = "SUSPENDIDO";
      $subscription->change_status();
      Response::success_json('Se ha suspendido la suscripción', $subscription, 200);
    } else
      Response::error_json(['message' => 'No existe la suscripción'], 200);
  }
}
