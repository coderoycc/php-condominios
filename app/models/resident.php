<?php

namespace App\Models;

use PDO;

class Resident extends User {
  private $con;
  public int $department_id;
  public Department $department;
  public Subscription $subscription;
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
  public function subscription(): Subscription {
    $this->subscription = new Subscription();
    if ($this->con) {
      if ($this->id_user) {
        $sql = "SELECT b.* FROM tblUsersSubscribed a
          INNER JOIN tblSubscriptions b
          ON a.subscription_id = b.id_subscription
          WHERE a.user_id = ?;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->id_user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row)
          $this->subscription->load($row);
      }
    }
    return $this->subscription;
  }

  public function subscription_valid(): bool {
    if ($this->con) {
      $sql = "SELECT b.* FROM tblUsersSubscribed a 
        INNER JOIN tblSubscriptions b 
        ON a.subscription_id = b.id_subscription
        WHERE a.user_id = ? AND b.expires_in > getdate();";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->id_user]);
      $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      if ($rows)
        return true;
    }
    return false;
  }
  public static function search_user_depa($con, $name_u, $depa_num = null): array {
    $sql = "SELECT * FROM tblUsers a INNER JOIN tblResidents b 
      ON a.id_user = b.user_id
      INNER JOIN tblDepartments c
      ON b.department_id = c.id_department
      WHERE a.role = 'resident' AND a.status = 1 AND (a.last_name LIKE '%$name_u%' OR a.first_name LIKE '%$name_u%')";
    if ($depa_num) {
      $sql .= " AND c.dep_number = '$depa_num'";
    }
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $response = [];
    foreach ($rows as $row) {
      $resident = new Resident();
      $resident->load($row);
      unset($resident->password);
      unset($resident->role);
      unset($resident->created_at);
      $depa = new Department();
      $depa->load($row);
      $resident->department = $depa;
      $response[] = $resident;
    }
    return $response;
  }
}
