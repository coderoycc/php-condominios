<?php

namespace App\Services;

use App\Models\Payment;
use App\Services\Interfaces\IPay;
use Ramsey\Uuid\Nonstandard\Uuid;

use const App\Config\BUSINESS_CODE;
use const App\Config\SERVICE_CODE;

class PayService implements IPay {
  public function new($con, $condominio, $amount, $gloss, $phoneNumber, $payment): mixed {
    return [];
  }
  public function subscription($con, $data, $annual, $condominio): array {
    $payment = new Payment($con);
    $payment->currency = 'BOB';
    $type = $data['type'];
    $resident = $data['resident'];
    $uuid = Uuid::uuid4();
    $payment->app_user_id = $resident->id_user;
    $payment->bussinessCode = BUSINESS_CODE;
    $payment->serviceCode = SERVICE_CODE;
    $payment->correlation_id = $uuid->toString();
    $payment->gloss = 'Suscripcion ' . $type->name;
    $payment->account = 'SUB';
    $payment->type = 'QR';
    $payment->amount = $annual ? $type->annual_price : $type->price;
    // realizamos la peticion api qr

    return [];
  }

  public function get_qr_byid($con, $qr_id, $condominio): mixed {
    return new Payment();
  }
}
if (!function_exists('pay')) {
  function pay(): IPay {
    return new PayService();
  }
}
