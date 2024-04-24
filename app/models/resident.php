<?php

namespace App\Models;

use App\Config\Database;

class Resident extends User {
  public int $department_id;
  public object $department;
  public object $subscription;
  public string $phone;
  public string $details;
  public function __construct($id_user = null) {
    if ($id_user) {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblUsers a INNER JOIN tblResidents b ON a.id_user = b.user_id WHERE a.id_user = $id_user;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) $this->load($row);
      else $this->objectNull();
    } else {
      $this->objectNull();
    }
  }
  public function save($pin = null) {
    $resp = parent::save($pin);
    if ($resp > 0) {
      try {
        if ($pin != null) $con = Database::getInstanceByPin($pin);
        else $con = Database::getInstace();
        $con->beginTransaction();
        $sql = "INSERT INTO tblResidents(user_id, department_id, phone, details) VALUES (?, ?, ?, ?);";
        $stmt = $con->prepare($sql);
        $rr = $stmt->execute([$this->id_user, $this->department_id, $this->phone, $this->details]);
        if ($rr) {
          $con->commit();
          $resp = $this->id_user;
        } else {
          parent::delete();
          $con->rollBack();
          $resp = -1;
        }
      } catch (\Throwable $th) {
        $con->rollBack();
        $resp = -1;
      }
    }
    return $resp;
  }

  public function objectNull() {
    parent::objectNull();
    $this->department_id = 0;
    $this->phone = "";
    $this->details = "";
    $this->role = "resident";
  }
  public function load($row) {
    parent::load($row);
    $this->department_id = $row["department_id"];
    $this->phone = $row["phone"];
    $this->details = $row["details"];
  }
  public function department() {
    if ($this->department_id) {
      $this->department = new Department($this->department_id);
    }
  }
}
