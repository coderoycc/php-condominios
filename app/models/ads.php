<?php

namespace App\Models;

use PDO;

class Ads {
  private $con;
  public int $id_ad;
  public string $description;
  public string $direct_to;
  public string $type; //IMAGEN | GIF | VIDEO
  public int $company_id;
  public string $content; // URL DEL VIDEO o URL ruta de la imagen
  public string $created;
  public string $start_date;
  public string $end_date;
  public function __construct($con = null, $id = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id) {
        $sql = "SELECT * FROM tblAds WHERE id_ad = ?;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_ad = 0;
    $this->description = "";
    $this->created = "";
    $this->type = "";
    $this->company_id = 0;
    $this->direct_to = "";
    $this->content = "";
    $this->start_date = "";
    $this->end_date = "";
  }
  public function load($row) {
    $this->id_ad = $row['id_ad'];
    $this->description = $row['description'];
    $this->created = $row['created'];
    $this->direct_to = $row['direct_to'];
    $this->content = $row['content'];
    $this->type = $row['type'];
    $this->company_id = $row['company_id'];
    $this->start_date = $row['start_date'];
    $this->end_date = $row['end_date'];
  }
  public function insert() {
    try {
      $sql = "INSERT INTO tblAds (description, direct_to, content, type, company_id, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?);";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->description, $this->direct_to, $this->content, $this->type, $this->company_id, $this->start_date, $this->end_date]);
      $this->id_ad = $this->con->lastInsertId();
      return $this->id_ad;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return 0;
  }
  /**
   * Retorna todos los publicitadores
   * @param PDO $con
   * @return mixed
   */
  static function all($con, $where = '') {
    try {
      $sql = "SELECT * FROM tblAds a INNER JOIN tblCompanies b ON b.id_company = a.company_id $where ORDER BY a.id_ad DESC";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
