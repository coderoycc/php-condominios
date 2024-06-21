<?php

namespace App\Models;

require_once(__DIR__ . '/service_detail.php');
class Services {
  private $con;
  public int $id_service;
  public int $user_id;
  public string $service_name;
  public int $service_name_id; // db_master
  public int $department_id;
  public string $code;
  public object $departmemt;

  public function __construct($con = null, $id_service = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_service) {
        $sql = "SELECT * FROM tblServices WHERE id_service = ?";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([$id_service])) {
          $row = $stmt->fetch();
          if ($row)
            $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_service = 0;
    $this->user_id = 0;
    $this->service_name = "";
    $this->service_name_id = 0;
    $this->department_id = 0;
    $this->code = "";
  }
  public function load($row) {
    $this->id_service = $row['id_service'];
    $this->user_id = $row['user_id'];
    $this->service_name = $row['service_name'];
    $this->service_name_id = $row['service_name_id'] ?? 0;
    $this->department_id = $row['department_id'];
    $this->code = $row['code'];
  }
  public function department($department = null) {
    if ($department) {
      $this->departmemt = $department;
    }
    return $this->departmemt;
  }
  public function save(): int {
    if ($this->con) {
      if ($this->id_service > 0) {
        $sql = "UPDATE tblServices SET user_id = ?, service_name = ?, service_name_id = ?, department_id = ?, code = ? WHERE id_service = ?";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->user_id, $this->service_name, $this->service_name_id, $this->department_id, $this->code, $this->id_service]);
        if ($res) return $this->id_service;
      } else {
        $sql = "INSERT INTO tblServices (user_id, service_name, service_name_id, department_id, code) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->user_id, $this->service_name, $this->service_name_id, $this->department_id, $this->code]);
        if ($res) {
          $this->id_service = $this->con->lastInsertId();
          return $this->id_service;
        }
      }
    }
    return -1;
  }
  public function delete() {
  }
  public static function list_by_department($con, $department_id) {
    try {
      $sql = "SELECT * FROM tblServices WHERE department_id = ?;";
      $stmt = $con->prepare($sql);
      $stmt->execute([$department_id]);
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
  public static function subs_all($con, $filters) {
    try {
      $sql = "SELECT a.id_subscription, a.valid, a.expires_in, a.code, b.name, c.dep_number, c.id_department FROM tblSubscriptions a 
        INNER JOIN tblSubscriptionType b ON a.type_id = b.id_subscription_type AND b.see_services = 1 AND a.expires_in > GETDATE()
        INNER JOIN tblDepartments c ON c.id_department = a.department_id;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
  public static function sum_department($con, $depa_id, $currYear) {
    try {
      $sql = "SELECT MONTH(month) as mes, ROUND(sum(amount), 2) as total FROM tblServiceDetail
        WHERE service_id IN (
          SELECT id_service FROM tblServices WHERE department_id = $depa_id
        )
        AND YEAR(month) = $currYear
        GROUP BY MONTH(month)";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function detail_for_month($con, $month, $year, $depa_id) {
    try {
      $sql = "SELECT * FROM tblServices a
        INNER JOIN tblServiceDetail b ON a.id_service = b.service_id AND a.department_id = $depa_id
        WHERE MONTH(b.month) = $month AND YEAR(b.month) = $year;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
