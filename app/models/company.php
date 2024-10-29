<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Company {
  private $con;
  public int $id_company;
  public string $company;
  public string $created_at;
  public string $line;
  public string $phone;
  public string $description;
  public function __construct($con = null, $id = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id) {
        $sql = "SELECT * FROM tblCompanies WHERE id_company = $id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) $this->load($row);
      }
    }
  }
  public function objectNull() {
    $this->id_company = 0;
    $this->company = "";
    $this->created_at = "";
    $this->line = "";
    $this->phone = "";
    $this->description = "";
  }
  public function load($row) {
    $this->id_company = $row['id_company'];
    $this->company = $row['company'];
    $this->created_at = $row['created_at'];
    $this->line = $row['line'] ?? '';
    $this->phone = $row['phone'] ?? '';
    $this->description = $row['description'] ?? '';
  }
  public function insert() {
    try {
      $sql = "INSERT INTO tblCompanies (company, line, phone, description) VALUES (:company, :line, :phone, :description)";
      $stmt = $this->con->prepare($sql);
      $stmt->execute(['company' => $this->company, 'description' => $this->description, 'line' => $this->line, 'phone' => $this->phone]);
      $this->id_company = $this->con->lastInsertId();
      return $this->id_company;
    } catch (PDOException $e) {
      var_dump($e);
    }
    return 0;
  }
  public static function get_companies($filters) {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCompanies";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $companies;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
}
