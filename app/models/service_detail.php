<?php

namespace App\Models;

use PDO;

class ServiceDetail {
  private $con = null;
  public int $id_service_detail;
  public int $service_id;
  public float $amount;
  public string $month;
  public function __construct($con = null, $id_service_detail = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_service_detail) {
        $sql = "SELECT * FROM tblServiceDetail WHERE id_service_detail = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id_service_detail]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_service_detail = 0;
    $this->service_id = 0;
    $this->amount = 0;
    $this->month = '';
  }
  public function load($row) {
    $this->id_service_detail = $row['id_service_detail'];
    $this->service_id = $row['service_id'];
    $this->amount = $row['amount'];
    $this->month = $row['month'];
  }
  public function insert(): int {
    if ($this->con == null)
      return -1;
    try {
      $sql = "INSERT INTO tblServiceDetail(service_id,amount,month) VALUES(?,?,?);";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->service_id, $this->amount, $this->month]);
      $this->id_service_detail = $this->con->lastInsertId();
      return $this->id_service_detail;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return -1;
  }
  public function update(): int {
    if ($this->con == null)
      return -1;
    try {
      $sql = "UPDATE tblServiceDetail SET service_id = ?, amount = ? WHERE id_service_detail = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->service_id, $this->amount, $this->id_service_detail]);
      return $this->id_service_detail;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return -1;
  }
  public static function get_list() {
  }
  /**
   * Devuelve un valor booleano para verificar si se registro el pago de un mes y año de un departamento
   * @param PDO $con
   * @param int $month
   * @param int $year
   * @param int $depa_id
   * @return bool
   */
  public static function verify_exist($con, $month, $year, $depa_id) {
    try {
      $sql = "SELECT * FROM tblServices a INNER JOIN tblServiceDetail b 
      ON a.id_service = b.service_id AND a.department_id = $depa_id
      WHERE MONTH(b.month) = $month AND YEAR(b.month) = $year";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($row) {
        return true;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  /**
   * Devuelve una lista de los pagos de un mes
   * @param mixed $con
   * @param mixed $month
   * @param mixed $year
   * @param mixed $depa_id
   * @return array
   */
  public static function list_depa_amounts($con, $date, $depa_id) {
    try {
      $sql = "SELECT * FROM tblServices a 
      LEFT JOIN tblServiceDetail b 
      ON a.id_service = b.service_id AND a.department_id = $depa_id
      WHERE b.[month] = '$date'";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $row;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
