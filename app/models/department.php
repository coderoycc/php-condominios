<?php

namespace App\Models;

class Department {
  private $con;
  public int $id_department;
  public string $dep_number;
  public int $bedrooms;
  public string $description;
  public function __construct($con = null, $id_department = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_department) {
        $sql = "SELECT * FROM tblDepartments WHERE id_department = :id_department";
        $stmt = $this->con->prepare($sql);
        $stmt->execute(['id_department' => $id_department]);
        $row = $stmt->fetch();
        if ($row) $this->load($row);
      }
    }
  }
  public function objectNull() {
    $this->id_department = 0;
    $this->dep_number = '0';
    $this->bedrooms = 0;
    $this->description = '';
  }
  public function load($row) {
    $this->id_department = $row['id_department'];
    $this->dep_number = $row['dep_number'];
    $this->bedrooms = $row['bedrooms'];
    $this->description = $row['description'];
  }
  public function save(): int {
    if ($this->con) {
      if ($this->id_department == 0) {
        $sql = "INSERT INTO tblDepartments (dep_number, bedrooms, description) VALUES (:dep_number, :bedrooms, :description)";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([
          'dep_number' => $this->dep_number,
          'bedrooms' => $this->bedrooms,
          'description' => $this->description
        ]);
        $this->id_department = $this->con->lastInsertId();
        return $this->id_department;
      } else {
        $sql = "UPDATE tblDepartments SET dep_number = :dep_number, bedrooms = :bedrooms, description = :description WHERE id_department = :id_department";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([
          'dep_number' => $this->dep_number,
          'bedrooms' => $this->bedrooms,
          'description' => $this->description,
          'id_department' => $this->id_department
        ]);
        return $this->id_department;
      }
    }
    return 0;
  }
  public function delete() {
    if ($this->con) {
      try {
        $sql = "DELETE FROM tblDepartments WHERE id_department = :id_department";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute(['id_department' => $this->id_department]);
        if ($res) {
          return true;
        }
      } catch (\Throwable $th) {
        var_dump($th);
        return false;
      }
    }
    return false;
  }

  public static function search($con, $q) {
    if ($con) {
      $sql = "SELECT * FROM tblDepartments WHERE dep_number LIKE '%$q%' OR description LIKE '%$q%';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    return [];
  }
  public static function get_all($con, $query = []) {
    if ($con) {
      $sql = "SELECT * FROM tblDepartments";

      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    return [];
  }
  public static function get_with_subs($con, $filters) {
    try {
      $sql = "SELECT a.*, b.*, c.name, tmp.cant FROM tblDepartments a 
        LEFT JOIN tblSubscriptions b ON a.id_department = b.department_id AND b.valid = 1 AND b.expires_in > getdate()
        LEFT JOIN tblSubscriptionType c ON c.id_subscription_type = b.type_id 
        LEFT JOIN (
          SELECT subscription_id, count(*) as cant FROM tblUsersSubscribed
          GROUP BY subscription_id
        ) tmp ON tmp.subscription_id = b.id_subscription
        ORDER BY b.expires_in ASC";

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
