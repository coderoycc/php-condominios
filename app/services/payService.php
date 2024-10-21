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

  public function pay(): Int {
    return 1;
  }
  public function confirm($con, $idPayment) {
    // return $this->get($con, $idPayment)->confirm();
  }
}

if (!function_exists('pay')) {
  function pay(): PayService {
    return new PayService();
  }
}
