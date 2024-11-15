<?php

namespace EPQr;
// **** *****
// Cambiar payment 'confirmed' y subscription 'status', >> UsersSubscribed user_id and subscription_id
// **********
use App\Config\Database;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Models\User;
use Helpers\Resources\HandleDates;

// require_once(__DIR__ . '/../app/models/');
require_once(__DIR__ . '/../app/config/accesos.php');
require_once(__DIR__ . '/../app/config/database.php');
require_once(__DIR__ . '/../app/models/payment.php');
require_once(__DIR__ . '/../app/models/subscription.php');
require_once(__DIR__ . '/../helpers/resources/dates.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$data = file_get_contents('php://input');
$body = json_decode($data, true);

if ($body['ResponseCode'] == '000' && $body['state']) {
  $collectors = $body['Collectors'];
  $service_data = $collectors[0]['value'];
  $payment_data = $collectors[1]['value'];
  $pin = $service_data['pin'];
  $depa_id = $service_data['depa_id'];
  $user_id = $service_data['user_id'];
  $pay_id = $payment_data['pay_id'];
  $type_id = $payment_data['type_id'];
  $period = $payment_data['period'];
  $period = intval($period);
  $con = Database::getInstanceByPinExterno($pin);

  if ($con) {
    $payment = new Payment($con, $pay_id);
    $user = new User($con, $user_id);
    $type = new Subscriptiontype($con, $type_id);
    if ($payment->idPayment > 0) {
      $payment->transaction_response = $data;
      $subscription = new Subscription($con, null);
      $subscription->type_id = $type_id;
      $subscription->paid_by = $user->id_user;
      $subscription->paid_by_name = $user->first_name;
      $subscription->period = 0;
      $subscription->nit = '000';
      $subscription->department_id = $depa_id;
      $subscription->expires_in = HandleDates::date_expire_month($period * $type->months_duration);
      $subscription->status = 'VALIDO';
      $subscription->code = $subscription->genCode();
      $subscription->limit = 1;
      if ($subscription->insert() > 0) {
        echo json_encode(['State' => '000', 'Message' => 'Transaccion exitosa', 'Data' => ['Id' => $subscription->id_subscription]]);
      } else {
        echo json_encode(['State' => '666', 'Message' => 'Transaccion errorea', 'Data' => ['Id' => 'E-0000']]);
      }
    }
  } else {
    echo json_encode(['State' => '666', 'Message' => 'Transaccion errorea', 'Data' => ['Id' => 'E-0000']]);
  }
} else {
  echo json_encode(['State' => '666', 'Message' => 'Transaccion errorea', 'Data' => ['Id' => 'E-0000']]);
}
