<?php

namespace App\Services;

require_once(__DIR__ . '/interfaces/IPay.php');
require_once(__DIR__ . '/../utils/pays/handlerPay.php');

use App\Models\Payment;
use App\Services\Interfaces\IPay;
use App\Utils\HandlerPays;
use Ramsey\Uuid\Nonstandard\Uuid;

use const App\Config\BUSINESS_CODE;
use const App\Config\SERVICE_CODE;

class PayService implements IPay {
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
    $payment->account = '1';
    $payment->amount = $annual ? $type->annual_price : $type->price;
    $payment->save();
    // realizamos la peticion api qr
    $res_qr = [];
    if ($payment->idPayment > 0) {
      $pay = new HandlerPays();
      $res_qr = $pay->load(
        $payment,
        $condominio,
        '1'
      )->pay();
      var_dump($res_qr);
      if ($res_qr['state'] == '00') {
        $payment->id_qr = $res_qr['data']['id'];
        $payment->update_qr();
      }
    }

    return ['payment' => $payment, 'qr_data' => $res_qr];
  }

  public function get_qr_byid($con, $qr_id, $condominio): array {
    return [];
  }
}
if (!function_exists('pay')) {
  function pay(): IPay {
    return new PayService();
  }
}
