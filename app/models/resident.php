<?php

namespace App\Models;


class Resident extends User {
  private $con;
  public int $department_id;
  public object $department;
  public object $subscription;
  public string $phone;
  public string $details;
  public function __construct($db = null, $id_user = null) {
    $this->objectNull();
    if ($db) {
      parent::__construct($db);
      $this->con = $db;
      if ($id_user) {
        $sql = "SELECT * FROM tblUsers a INNER JOIN tblResidents b ON a.id_user = b.user_id WHERE a.id_user = $id_user;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) $this->load($row);
      }
    }
  }
  public function save() {
    $resp = parent::save();
    if ($resp > 0) {
      try {
        $this->con->beginTransaction();
        $sql = "INSERT INTO tblResidents(user_id, department_id, phone, details) VALUES (?, ?, ?, ?);";
        $stmt = $this->con->prepare($sql);
        $rr = $stmt->execute([$this->id_user, $this->department_id, $this->phone, $this->details]);
        if ($rr) {
          $this->con->commit();
          $resp = $this->id_user;
        } else {
          parent::delete();
          $this->con->rollBack();
          $resp = -1;
        }
      } catch (\Throwable $th) {
        $this->con->rollBack();
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
    $this->phone = $row["phone"] ?? '0';
    $this->details = $row["details"] ?? '';
  }
  public function department() {
    if ($this->con) {
      if ($this->department_id) {
        $this->department = new Department($this->con, $this->department_id);
      }
    }
  }
}
