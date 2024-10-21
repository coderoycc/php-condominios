<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Payment;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class PaymentController {
  public function verify($data) {
    if (!Request::required(['pay_id', 'pin'], $data))
      Response::error_json(['message' => 'Parametro pay_id y pin son necesarios'], 200);

    $con = Database::getInstanceByPin($data['pin']);
    $payment = new Payment($con, $data['pay_id']);
    if ($payment->idPayment > 0) {
      if ($payment->confirmed == 1) {
        Response::success_json('El pago ya fue confirmado', [], 200);
      } else
        Response::error_json(['message' => 'El pago no fue confirmado'], 200);
    } else
      Response::error_json(['message' => 'El pago no existe'], 200);
  }
  public function pay_with_card() {
  }
  public function pay_with_bank() {
  }
  public function pay_with_wallet() {
  }
}
