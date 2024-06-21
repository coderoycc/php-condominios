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
    Response::success_json('Tipos de suscripción', $data);
  }
  public function types_web($data) {
    $condominio = DBWebProvider::session_get_condominio();
    if (isset($condominio->pin)) {
      $data_subs = Subscription::getTypes($condominio->pin);
      ob_start();
      include(__DIR__ . '/../views/subscription/type_list.php');
      $html = ob_get_clean();
      Response::html($html);
    } else {
      Response::html("<h1 class='text-center'>Ocurrió un error en instance conection</h1>");
    }
  }
  public function subscribe($data, $files = null) {
    if (!Request::required(['type_id', 'user_id', 'pin', 'annual'], $data))
      Response::error_json(['message' => 'Parametros faltantes']);

    $con = Database::getInstanceByPin($data['pin']);
    $type = new Subscriptiontype($con, $data['type_id']);
    $resident = new Resident($con, $data['user_id']);
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
        $subscription->valid = 1;
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
}
