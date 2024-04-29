<?php

namespace App\Models;

require_once __DIR__ . '/subscriptiontype.php';

use App\Models\Subscriptiontype;

class Subscription {
  private $con;
  public int $id_subscription;
  public int $type_id;
  public string $paid_in;
  public int $paid_by;
  public string $paid_by_name;
  public int $period;
  public string $nit;
  public int $department_id;
  public string $expires_in;
  public int $valid;
  public int $limit;
  public string $code;
  public Subscriptiontype $type;
  public Department $department;

  public function __construct($db = null, $id_subscription = null) {
    $this->objectNull();
    if ($db) {
      $this->con = $db;
      if ($id_subscription) {
        $stmt = $this->con->query("SELECT * FROM tblSubscriptions WHERE id_subscription = $id_subscription");
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
          $this->load($row);
        }
      }
    }
  }
  public function load($row) {
    $this->id_subscription = $row['id_subscription'];
    $this->type_id = $row['type_id'];
    $this->paid_in = $row['paid_in'];
    $this->paid_by = $row['paid_by'];
    $this->paid_by_name = $row['paid_by_name'];
    $this->period = $row['period'];
    $this->nit = $row['nit'];
    $this->department_id = $row['department_id'];
    $this->expires_in = $row['expires_in'];
    $this->valid = $row['valid'];
    $this->code = $row['code'];
    $this->limit = $row['limit'];
  }
  public function objectNull() {
    $this->id_subscription = 0;
    $this->type_id = 0;
    $this->paid_in = "";
    $this->paid_by = 0;
    $this->paid_by_name = "";
    $this->period = 0;
    $this->nit = "";
    $this->department_id = 0;
    $this->expires_in = "1970-01-01 00:00:00";
    $this->valid = 0;
    $this->code = "";
    $this->limit = 0;
  }
  public function type() {
    if ($this->con) {
      $this->type = new Subscriptiontype($this->con, $this->type_id);
    }
  }
  public function genCode() {
    return strtoupper(substr(uniqid(), -6));
  }
  public static function getTypes($pin) {
    return Subscriptiontype::getTypes($pin);
  }
  public static function getSusbscriptionUser($con = null, $id_user): Subscription {
    $subscription = new Subscription();
    if ($con) {
      $sql = "SELECT TOP 1 * FROM tblUsersSubscribed a INNER JOIN tblSubscriptions b ON a.subscription_id = b.id_subscription WHERE a.user_id = $id_user ORDER BY b.expires_in DESC;";
      $stmt = $con->query($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) {
        $subscription->load($row);
      }
    }
    return $subscription;
  }
  public static function getSubscriptionByCode($con = null, $code): array {
    $resp = ['valid' => false, 'limit_reached' => true, 'subs_id' => 0];
    if ($con) {
      $sql = "SELECT * FROM tblSubscriptions a INNER JOIN tblUsersSubscribed b ON a.id_subscription = b.subscription_id WHERE a.code = '$code';";
      $stmt = $con->query($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      if ($rows) {
        $row = $rows[0];
        $resp['valid'] = true;
        $resp['subs_id'] = $row['id_subscription'];
        if (count($rows) < $row['limit']) {
          $resp['limit_reached'] = false;
        }
      }
    }
    return $resp;
  }
  public static function addUserSubscription($con = null, $id_user, $id_subscription) {
    if ($con) {
      $sql = "INSERT INTO tblUsersSubscribed (user_id, subscription_id) VALUES ($id_user, $id_subscription);";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      return $res;
    }
    return false;
  }
}
