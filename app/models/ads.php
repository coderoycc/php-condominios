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
  public string $target; // F: feminino, M: masculino, O: todos
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
    $this->target = "";
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
    $this->target = $row['target'] ?? 'O';
  }
  public function insert() {
    try {
      $sql = "INSERT INTO tblAds (description, direct_to, content, type, company_id, start_date, end_date, target) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->description, $this->direct_to, $this->content, $this->type, $this->company_id, $this->start_date, $this->end_date, $this->target]);
      $this->id_ad = $this->con->lastInsertId();
      return $this->id_ad;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return 0;
  }
  public function update() {
    try {
      $sql = "UPDATE tblAds SET description = ?, direct_to = ?, company_id = ?, start_date = ?, end_date = ?, target = ? WHERE id_ad = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->description, $this->direct_to, $this->company_id, $this->start_date, $this->end_date, $this->target, $this->id_ad]);
      return $res;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public function delete() {
    try {
      $this->delete_file();
      $sql = "DELETE FROM tblAds WHERE id_ad = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->id_ad]);
      return $res;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public function delete_file() {
    try {
      $file = $this->content;
      $file = __DIR__ . "/../../public/ads/$file";
      if (file_exists($file)) {
        unlink($file);
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }
  /**
   * Retorna todos los publicitadores 'a' tblAds, 'b' tblCompanies
   * @param PDO $con
   * @return mixed
   */
  static function all($con, $where = '') {
    try {
      $sql = "SELECT a.*, b.id_company, b.company, b.phone, b.description as description_company, b.url, b.status FROM tblAds a INNER JOIN tblCompanies b ON b.id_company = a.company_id $where ORDER BY a.id_ad DESC";
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
