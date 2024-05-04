<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Providers\DBWebProvider;
use Helpers\Resources\HandleDates;
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
    if (!Request::required(['type_id', 'user_id', 'pin'], $data))
      Response::error_json(['message' => 'Parametros faltantes']);

    $con = Database::getInstanceByPin($data['pin']);
    $type = new Subscriptiontype($con, $data['type_id']);
    $resident = new Resident($con, $data['user_id']);
    if ($type->price > 0) {
      $periods = $data['periods'] ?? 1;
      $precio = $periods * $type->price;
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
}
