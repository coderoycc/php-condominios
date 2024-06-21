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
          $this->load($id_service_detail);
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
  public static function get_list() {
  }
  public static function get_amounts() {
  }
}
