<?php

namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;

class Department {
  public int $id_department;
  public string $dep_number;
  public int $bedrooms;
  public string $description;
  public function __construct($id_department = null) {
    if ($id_department) {
      $con = Database::getInstance();
      $sql = "SELECT * FROM tblDepartments WHERE id_department = :id_department";
      $stmt = $con->prepare($sql);
      $stmt->execute(['id_department' => $id_department]);
      $row = $stmt->fetch();
      if ($row) $this->load($row);
      else  $this->objectNull();
    } else {
      $this->objectNull();
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

  public static function search($q, $pin = null) {
    if ($pin) {
      $condominio = Accesos::getCondominio($pin);
      if (isset($condominio['dbname'])) {
        $con = Database::getInstanceX($condominio['dbname']);
        $sql = "SELECT * FROM tblDepartments WHERE dep_number LIKE '%$q%' OR description LIKE '%$q%';";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
      }
    } else {
      $con = Database::getInstance();
      if ($con) {
        $sql = "SELECT * FROM tblDepartments WHERE dep_number LIKE '%$q%' OR description LIKE '%$q%';";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
      }
    }
    return [];
  }
}
