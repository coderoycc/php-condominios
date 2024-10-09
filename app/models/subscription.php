<?php

namespace App\Models;

require_once __DIR__ . '/subscriptiontype.php';

use App\Models\Subscriptiontype;
use Exception;
use PDO;

class Subscription {
  private $con;
  public int $id_subscription;
  public int $type_id;
  public string $subscribed_in;
  public int $paid_by;
  public string $paid_by_name;
  public int $period;
  public string $nit;
  public int $department_id;
  public string $expires_in;
  public int $limit;
  public string $code;
  public string $status; // 
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
    $this->subscribed_in = $row['subscribed_in'];
    $this->paid_by = $row['paid_by'];
    $this->paid_by_name = $row['paid_by_name'] ?? '';
    $this->period = $row['period'] ?? 0;
    $this->nit = $row['nit'];
    $this->department_id = $row['department_id'];
    $this->expires_in = $row['expires_in'];
    $this->code = $row['code'];
    $this->limit = $row['limit'];
    $this->status = $row['status'] ?? '';
  }

  public function insert() {
    try {
      $this->con->beginTransaction();
      $sql = "INSERT INTO tblSubscriptions (type_id, paid_by, paid_by_name, period, nit, department_id, expires_in, code, limit, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->type_id, $this->paid_by, $this->paid_by_name, $this->period, $this->nit, $this->department_id, $this->expires_in, $this->code, $this->limit, $this->status]);
      if ($res) {
        $this->id_subscription = $this->con->lastInsertId();
        $sqlSubsUser = "INSERT INTO tblUsersSubscribed (user_id, subscription_id) VALUES (?, ?)";
        $stmtSubsUser = $this->con->prepare($sqlSubsUser);
        $resSubsUser = $stmtSubsUser->execute([$this->paid_by, $this->id_subscription]);
        if ($resSubsUser) {
          $this->con->commit();
          return $this->id_subscription;
        } else {
          $this->con->rollBack();
          return -1;
        }
      } else {
        $this->con->rollBack();
        return -1;
      }
    } catch (\Throwable $th) {
      $this->con->rollBack();
      var_dump($th);
    }
    return -1;
  }

  public function change_plan() {
    try {
      $sql = "UPDATE tblSubscriptions SET type_id = ?, subscribed_in = ?, expires_in = ? WHERE id_subscription = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->type_id, $this->subscribed_in, $this->expires_in, $this->id_subscription]);
      if ($res) {
        return $this->id_subscription;
      } else {
        return -1;
      }
    } catch (\Throwable $th) {
      var_dump($th);
      throw new Exception("Error Processing Request", 1);
    }
  }
  /**
   * Funcion que unicamente cambia el estado
   */
  public function change_status() {
    try {
      if ($this->con) {
        $sql = "UPDATE tblSubscriptions SET status = ? WHERE id_subscription = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->status, $this->id_subscription]);
      }
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), 1);
    }
  }

  public function objectNull() {
    $this->id_subscription = 0;
    $this->type_id = 0;
    $this->subscribed_in = "";
    $this->paid_by = 0;
    $this->paid_by_name = "";
    $this->period = 0;
    $this->nit = "";
    $this->department_id = 0;
    $this->expires_in = "1970-01-01 00:00:00";
    $this->code = "";
    $this->limit = 0;
    $this->status = "";
  }
  public function type() {
    if ($this->con) {
      $this->type = new Subscriptiontype($this->con, $this->type_id);
    }
  }
  public function genCode() {
    return strtoupper(substr(uniqid(), -6));
  }
  public static function get_users_connected($con, $sub_id) {
    try {
      $sql = "SELECT count(*) as cantidad FROM tblUsersSubscribed a INNER JOIN tblSubscriptions b 
      ON a.subscription_id = b.id_subscription WHERE b.id_subscription = $sub_id;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row['cantidad'];
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return 0;
  }
  public static function getTypes($pin) {
    return Subscriptiontype::getTypes($pin, null);
  }
  public static function getSusbscriptionUser($con = null, $id_user): Subscription {
    $subscription = new Subscription($con);
    if ($con) {
      $sql = "SELECT TOP 1 * FROM tblUsersSubscribed a INNER JOIN tblSubscriptions b ON a.subscription_id = b.id_subscription WHERE a.user_id = $id_user ORDER BY b.expires_in DESC;";
      $stmt = $con->query($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) {
        $subscription->load($row);
      }
    }
    return $subscription;
  }
  public static function getSubscriptionByCode($con = null, $code, $depa_id): array {
    $resp = ['valid' => false, 'limit_reached' => true, 'subs_id' => 0, 'depa_ok' => true];
    if ($con) {
      $sql = "SELECT * FROM tblSubscriptions a INNER JOIN tblUsersSubscribed b ON a.id_subscription = b.subscription_id WHERE a.code = '$code';";
      $stmt = $con->query($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows) {
        $row = $rows[0];
        $resp['valid'] = true;
        $resp['subs_id'] = $row['id_subscription'];
        if (count($rows) < $row['limit']) {
          $resp['limit_reached'] = false;
        }
        if ($row['department_id'] != $depa_id) {
          $resp['depa_ok'] = false;
        }
      }
    }
    return $resp;
  }
  public static function addUserSubscription($con = null, $id_user, $id_subscription) {
    if ($con == null) return false;
    try {
      $sql = "SELECT * FROM tblUsersSubscribed WHERE user_id = $id_user AND subscription_id = $id_subscription;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) {
        return true;
      }
      $sql = "INSERT INTO tblUsersSubscribed (user_id, subscription_id) VALUES ($id_user, $id_subscription);";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      return $res;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public static function verify_subscription_free($con, $type_id, $resident) {
    try {
      $max = 2;
      if ($resident->id_user) {
        $year = date('Y');
        // Todos las suscripciones de tipo 'gratuito <TYPE_ID>' que sean de este año que esten asociados al departamento del usuario que solicita la suscripcion gratuita, tambien el usuario debe estar activo (1)
        $sql = "SELECT COUNT(*) as cantidad FROM tblUsersSubscribed a INNER JOIN tblUsers b 
          ON a.user_id = b.id_user
          WHERE a.subscription_id IN (
            SELECT id_subscription FROM tblSubscriptions WHERE type_id = $type_id AND YEAR(expires_in) >= $year AND department_id = $resident->department_id
          ) AND b.status = 1";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $cantidad = $stmt->fetch()['cantidad'];
        if ($max > $cantidad)
          return true;
        else
          return false;
      } else
        return false;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public static function get_subscriptions_all($con, array $filters) {
    $data_rows = [];
    try {
      $start = $filters['start'] ?? date('Y') . "-01-01T00:00:00";
      $end = $filters['end'] ?? date('Y') . "-12-31T00:00:00";
      $sql = "SELECT a.name, a.price, a.see_lockers, a.see_services, b.*, d.amount, d.created_at, d.confirmed
      FROM tblSubscriptionType a INNER JOIN 
      tblSubscriptions b ON a.id_subscription_type = b.type_id
      LEFT JOIN tblPaymentSubscriptions c ON c.subscription_id = b.id_subscription
      LEFT JOIN tblPayments d ON c.payment_id = d.idPayment
      WHERE b.subscribed_in BETWEEN ? AND ?;";
      // echo $sql;
      $stmt = $con->prepare($sql);
      $stmt->execute([$start, $end]);
      $data_rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      // var_dump($th);
    }
    return $data_rows;
  }
  public static function get_subscriptions_by_typeId($con, $id, array $filters) {
    $data_rows = [];
    try {
      $start = $filters['start'] ?? date('Y') . "-01-01T00:00:00";
      $end = $filters['end'] ?? date('Y') . "-12-31T00:00:00";

      $sql = "SELECT a.name, a.price, a.see_lockers, a.see_services, b.*, d.amount, d.created_at, d.confirmed, u.cellphone
      FROM tblSubscriptionType a INNER JOIN 
      tblSubscriptions b ON a.id_subscription_type = b.type_id
      LEFT JOIN tblPaymentSubscriptions c ON c.subscription_id = b.id_subscription
      LEFT JOIN tblPayments d ON c.payment_id = d.idPayment
			LEFT JOIN tblUsers u ON u.id_user = b.paid_by
      WHERE b.type_id = ? AND b.subscribed_in BETWEEN '$start' AND '$end';";
      $stmt = $con->prepare($sql);
      $stmt->execute([$id]);
      $data_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return $data_rows;
  }
  public static function get_department_subscription($con, $depa_id, $filters = []) {
    try {
      $status = isset($filters['status']) ? "a.status = '" . $filters['status'] . "'" : '';
      $no_expired = isset($filters['no_expired']) ? "a.expires_in > GETDATE()" : '';
      $where = $status != '' ? "AND $status" : '';
      $where .= $no_expired != '' ? "AND $no_expired" : '';
      $sql = "SELECT a.*, b.name FROM tblSubscriptions a INNER JOIN tblSubscriptionType b 
          ON a.type_id = b.id_subscription_type
          WHERE a.department_id = $depa_id $where ORDER BY a.expires_in DESC;";
      $stmt = $con->query($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function get_subscriptions_by_department($con, $depa_id) {
    try {
      $sql = "SELECT a.*, b.name as type FROM tblSubscriptions a INNER JOIN tblSubscriptionType b
          ON a.type_id = b.id_subscription_type
          WHERE a.department_id = $depa_id ORDER BY a.expires_in DESC;";
      $stmt = $con->query($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      throw new Exception("Error suscrpitions department", 500);
    }
  }
}
