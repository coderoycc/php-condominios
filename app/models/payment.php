<?php

namespace App\Models;

use Exception;
use Ramsey\Uuid\Uuid;

use const App\Config\BUSINESS_CODE;

use function App\Services\pay;

class Payment {
  private $con;
  public int $idPayment;
  public string $currency;
  public float $amount;
  public string $gloss;
  public string $type;
  public string $correlation_id;
  public string $serviceCode;
  public string $app_user_id;
  public string $bussinessCode;
  public string $transaction_response;
  public int $confirmed; //cuando se confirmo el pago
  public string $created_at;
  public int $id_qr;
  public string $expiration_qr;
  public string $account;
  public function __construct($con = null, $idPayment = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($idPayment) {
        $sql = "SELECT * FROM tblPayments WHERE idPayment = ?";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([$idPayment])) {
          $row = $stmt->fetch();
          if ($row)
            $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->idPayment = 0;
    $this->currency = "";
    $this->amount = 0;
    $this->gloss = "";
    $this->type = "";
    $this->correlation_id = "";
    $this->serviceCode = "";
    $this->app_user_id = "";
    $this->bussinessCode = "";
    $this->transaction_response = "";
    $this->confirmed = 0;
    $this->created_at = "";
    $this->id_qr = 0;
    $this->expiration_qr = "";
    $this->account = "";
  }
  public function load($row) {
    $this->idPayment = $row['idPayment'];
    $this->currency = $row['currency'];
    $this->amount = $row['amount'];
    $this->gloss = $row['gloss'];
    $this->type = $row['type'];
    $this->correlation_id = $row['correlation_id'];
    $this->serviceCode = $row['serviceCode'] ?? '';
    $this->app_user_id = $row['app_user_id'] ?? 0;
    $this->bussinessCode = $row['bussinessCode'] ?? '';
    $this->transaction_response = $row['transaction_response'] ?? '';
    $this->confirmed = $row['confirmed'];
    $this->created_at = $row['created_at'];
    $this->id_qr = $row['id_qr'] ?? 0;
    $this->expiration_qr = $row['expiration_qr'] ?? '1970-01-01 00:00:00';
    $this->account = $row['account'] ?? '';
  }
  public function save() {
    if ($this->con) {
      try {
        $sql = "INSERT INTO tblPayments (currency, amount, gloss, type, correlation_id, serviceCode, app_user_id, bussinessCode, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->currency, $this->amount, $this->gloss, $this->type, $this->correlation_id, $this->serviceCode, $this->app_user_id, $this->bussinessCode, $this->account]);
        $this->idPayment = $this->con->lastInsertId();
        return $this->idPayment;
      } catch (\Throwable $th) {
        var_dump($th);
      }
      return -1;
    }
  }
  public function payment_confirm() {
    if ($this->con) {
      try {
        $sql = "UPDATE tblPayments SET transaction_response = ?, confirmed = ? WHERE idPayment = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->transaction_response, $this->confirmed, $this->idPayment]);
        if ($res)
          return true;
        return false;
      } catch (\Throwable $th) {
        var_dump($th);
      }
      return false;
    }
  }
  public function update_qr() {
    if ($this->con) {
      try {
        $sql = "UPDATE tblPayments SET id_qr = ? WHERE idPayment = ? ";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->id_qr, $this->idPayment]);
        if ($res)
          return true;
      } catch (\Throwable $th) {
        var_dump($th);
      }
      return false;
    }
  }
  public static function relation_payment_subscription($con, $id_payment, $id_subscription) {
    if ($con) {
      try {
        $sql = "INSERT INTO tblPaymentSubscriptions (payment_id, subscription_id) VALUES (?, ?);";
        $stmt = $con->prepare($sql);
        $stmt->execute([$id_payment, $id_subscription]);
        return true;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
    }
  }
}
