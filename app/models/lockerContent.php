<?php

namespace App\Models;

use PDO;

class LockerContent {
  private $con;
  public int $id_content;
  public string $content;
  public int $locker_id;
  public int $user_id_target;
  public int $department_id;
  public string $received_at;
  public function __construct($con = null, $id_content = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_content == null)
        return;
      $sql = "SELECT * FROM tblLockerContent WHERE id_content = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$id_content]);
      $row = $stmt->fetch();
      if ($row)
        $this->load($row);
    }
  }
  public function objectNull() {
    $this->id_content = 0;
    $this->content = '';
    $this->locker_id = 0;
    $this->user_id_target = 0;
    $this->received_at = '';
    $this->department_id = 0;
  }
  public function load($row) {
    $this->id_content = $row['id_content'];
    $this->content = $row['content'];
    $this->locker_id = $row['locker_id'];
    $this->user_id_target = $row['user_id_target'];
    $this->received_at = $row['received_at'];
    $this->department_id = $row['department_id'] ?? 0;
  }
  public function save() {
    if ($this->con) {
      $sql = "INSERT INTO tblLockerContent (content, locker_id, user_id_target, department_id) VALUES (?, ?, ?, ?)";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->content, $this->locker_id, $this->user_id_target, $this->department_id]);
      $this->id_content = $this->con->lastInsertId();
      $this->received_at = date('Y-m-d H:i:s');
      return $this->id_content;
    }
    return -1;
  }
  /**
   * Devuelve el historial de correspondencia recibida o enviada de un departamento
   * @param PDO $con
   * @param int $depa_id 
   * @param string $bandeja 'ENTRADA' o 'SALIDA'
   * @return mixed
   */
  public static function get_list_department($con, $depa_id, $bandeja) {
    try {
      $sql = "SELECT a.id_content, a.content, a.received_at, b.locker_number, a.department_id , b.type, b.in_out 
        FROM tblLockerContent a INNER JOIN tblLockers b 
        ON a.locker_id = b.id_locker
        WHERE a.user_id_target = $depa_id AND b.in_out = '$bandeja'
        ORDER BY a.id_content DESC;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
