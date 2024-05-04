<?php

namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;

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

  public static function search($con, $q) {
    if ($con) {

      $sql = "SELECT * FROM tblDepartments WHERE dep_number LIKE '%$q%' OR description LIKE '%$q%';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    return [];
  }
}
