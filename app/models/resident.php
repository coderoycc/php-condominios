<?php

namespace App\Models;

use App\Config\Database;

class Resident extends User {
  public int $department_id;
  public Department | null $department = null;
  public Subscription | null $subscription = null;
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

  public function objectNull() {
    parent::objectNull();
    $this->department_id = 0;
    $this->phone = "";
    $this->details = "";
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

  public function subscription() {
  }
}
