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
  public string $url;
  public int $status;
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
    $this->url = "";
    $this->status = 0;
  }
  public function load($row) {
    $this->id_company = $row['id_company'];
    $this->company = $row['company'];
    $this->created_at = $row['created_at'];
    $this->line = $row['line'] ?? '';
    $this->phone = $row['phone'] ?? '';
    $this->description = $row['description'] ?? '';
    $this->url = $row['url'] ?? '';
    $this->status = $row['status'] ?? 0;
  }
  public function insert() {
    try {
      $sql = "INSERT INTO tblCompanies (company, line, phone, description, url) VALUES (:company, :line, :phone, :description, :url)";
      $stmt = $this->con->prepare($sql);
      $stmt->execute(['company' => $this->company, 'description' => $this->description, 'line' => $this->line, 'phone' => $this->phone, 'url' => $this->url]);
      $this->id_company = $this->con->lastInsertId();
      return $this->id_company;
    } catch (PDOException $e) {
      var_dump($e);
    }
    return 0;
  }
  public function update() {
    try {
      $sql = "UPDATE tblCompanies SET company = :company, description = :description, line = :line, phone = :phone, url = :url, status = :status WHERE id_company = :id_company";
      $stmt = $this->con->prepare($sql);
      $stmt->execute(['company' => $this->company, 'description' => $this->description, 'line' => $this->line, 'phone' => $this->phone, 'url' => $this->url, 'status' => $this->status, 'id_company' => $this->id_company]);
      return true;
    } catch (PDOException $e) {
      var_dump($e);
    }
    return false;
  }
  public static function get_companies($filters) {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCompanies WHERE status = 1;";
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
