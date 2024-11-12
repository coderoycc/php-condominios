<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
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
    $subscription->type_id = $data['type']->id_type;
    $subscription->paid_by = $data['resident']->id_user;
    $subscription->paid_by_name = $data['resident']->first_name;
    $subscription->period = 0;
    $subscription->nit = $data['nit'];
    $subscription->department_id = $data['resident']->department_id;
    $subscription->expires_in = HandleDates::date_expire_month(1);
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
}
if (!function_exists('subscription')) {
  function subscription() {
    return new SusbcriptionService();
  }
}
