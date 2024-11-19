<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Models\User;
use Helpers\Resources\HandleDates;
use PDO;

class SusbcriptionService {
  public function get($con, $id): Subscription {
    return new Subscription($con, $id);
  }
  public function subscribe($con, $data, $annual = false): Subscription {
    $subscription = new Subscription($con, null);
    $period = $annual ? 12 : 6;
    $subscription->type_id = $data['type']->id;
    $subscription->paid_by = $data['resident']->id_user;
    $subscription->paid_by_name = $data['resident']->first_name;
    $subscription->period = $period;
    $subscription->nit = $data['nit'];
    $subscription->department_id = $data['resident']->department_id;
    $subscription->expires_in = HandleDates::date_expire_month($period);
    $subscription->code = $subscription->genCode();
    $subscription->limit = 3;
    $subscription->status = 'POR PAGAR';

    if ($subscription->insert() > 0) {
      return $subscription;
    }
    return new Subscription();
  }
  public function subscribe_free($con, $data): Subscription {
    $subscription = new Subscription($con, null);
    $subscription->type_id = $data['type']->id;
    $subscription->paid_by = $data['resident']->id_user;
    $subscription->paid_by_name = $data['resident']->first_name;
    $subscription->period = 0;
    $subscription->nit = $data['nit'];
    $subscription->department_id = $data['resident']->department_id;
    $subscription->expires_in = HandleDates::date_expire_month($data['type']->months_duration);
    $subscription->status = 'VALIDO';
    $subscription->code = $subscription->genCode();
    $subscription->limit = 1;

    if ($subscription->insert() > 0)
      return $subscription;

    return new Subscription();
  }
  /**
   * Relaciona la suscripcion con el pago realizado
   * @param PDO $con
   * @param int $id_subscription
   * @param int $id_payment
   * @return void
   */
  public function add_sub($con, $id_subscription, $id_payment) {
    Payment::relation_payment_subscription($con, $id_payment, $id_subscription);
  }
  /**
   * Crea un nuevo pago con una suscripcion
   * @param PDO $con
   * @param int $id_subscription
   * @param int $type_id
   * @return Subscription
   */
  public function new_plan($con, $id_subscription, $type_id, $user_id, $start_date, $suspend = true) {
    $payment = new Payment($con, null);
    $type = new Subscriptiontype($con, $type_id);
    $user = new Resident($con, $user_id);
    $sub_ant = new Subscription($con, $id_subscription);
    $subscription = new Subscription($con, null);
    if ($user->id_user == 0 || $type->id == 0)
      return $subscription;

    $payment->account = '1';
    $payment->amount = $type->price;
    $payment->gloss = 'Pago suscripcion ' . $type->name;
    $payment->type = 'EXTERNO'; // tipo de pago
    $payment->confirmed = 1;
    $payment->currency = 'BOB';
    $payment->app_user_id = $user->id_user;
    $payment->save();

    // agregar nueva suscripcion
    $subscription->type_id = $type->id;
    $subscription->paid_by = $user->id_user;
    $subscription->paid_by_name = $user->first_name;
    $subscription->period = $type->months_duration;

    if ($suspend) {
      $sub_ant->status = 'SUSPENDIDO';
      $sub_ant->change_status();
    }
    $subscription->department_id = $sub_ant->department_id;
    $subscription->expires_in = HandleDates::add_months_to_date($start_date, $type->months_duration);
    $subscription->code = $subscription->genCode();
    $subscription->limit = $type->max_users;
    $subscription->status = 'VALIDO';
    $subscription->insert();
    return $subscription;
  }
}
if (!function_exists('subscription')) {
  function subscription() {
    return new SusbcriptionService();
  }
}
