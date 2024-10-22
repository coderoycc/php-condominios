<?php

namespace App\Services;

use App\Models\Payment;
use PDO;

class PayService {
  /**
   * Retorna una nuevo objeto de modelo payments
   * @param PDO $con
   * @param int $idPayment
   * @return Payment
   */
  public function get($con, $idPayment): Payment {
    return new Payment($con, $idPayment);
  }

  public function subscription($con, $data, $annual = false): Payment {
    $payment = new Payment($con, null);
    $price = $annual ? $data['type']->annual_price : $data['type']->price;
    $payment->amount = $price;
    $payment->app_user_id = $data['resident']->id_user;
    $payment->gloss = 'Pago suscripcion ' . $data['type']->name;
    return $payment;
  }
  public function confirm($con, $idPayment) {
    // return $this->get($con, $idPayment)->confirm();
  }
  /**
   * Relaciona un pago con una suscription
   */
  public function add_sub($con, $id_subscription, $id_payment) {
    return Payment::relation_payment_subscription($con, $id_payment, $id_subscription);
  }
}

if (!function_exists('pay')) {
  function pay(): PayService {
    return new PayService();
  }
}
