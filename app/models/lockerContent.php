<?php

namespace App\Models;

use Helpers\Resources\HandleDates;
use PDO;

class LockerContent {
  private $con;
  public int $id_content;
  public string $content;
  public int $locker_id;
  public int $user_id_target;
  public int $department_id;
  public string $received_at;
  public int $received_by;
  public int $delivered; //0: NO ENTREGADO, 1: ENTREGADO
  public int $shipping_id; // unicamente para envios de salida.
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
    $this->received_by = 0;
    $this->delivered = 0;
    $this->shipping_id = 0;
  }
  public function load($row) {
    $this->id_content = $row['id_content'];
    $this->content = $row['content'];
    $this->locker_id = $row['locker_id'];
    $this->user_id_target = $row['user_id_target'];
    $this->received_at = $row['received_at'];
    $this->department_id = $row['department_id'] ?? 0;
    $this->received_by = $row['received_by'] ?? 0;
    $this->delivered = $row['delivered'] ?? 0;
    $this->shipping_id = $row['shipping_id'] ?? 0;
  }
  public function save() {
    if ($this->con) {
      $sql = "INSERT INTO tblLockerContent (content, locker_id, user_id_target, department_id, received_by) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->content, $this->locker_id, $this->user_id_target, $this->department_id, $this->received_by]);
      $this->id_content = $this->con->lastInsertId();
      $this->received_at = date('Y-m-d H:i:s');
      return $this->id_content;
    }
    return -1;
  }
  /**
   * Actualiza el shipping_id de la tabla tblLockerContent con el ID del envio
   * @param int $shipping_id
   * @return bool
   */
  public function set_shipping_id($shipping_id) {
    if ($this->con == null)
      return false;
    try {
      $sql = "UPDATE tblLockerContent SET shipping_id = ? WHERE id_content = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$shipping_id, $this->id_content]);
      return true;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public function change_delivered() {
    try {
      if ($this->con) {
        $sql = "UPDATE tblLockerContent SET delivered = ? WHERE id_content = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->delivered, $this->id_content]);
        return $this->id_content;
      }
    } catch (\Throwable $th) {
      // throw new ErrorException();
      var_dump($th);
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
      $sql = "SELECT TOP 10 a.id_content, a.content, a.received_at, a.received_by, a.delivered, b.locker_number, a.department_id , b.type, b.in_out 
        FROM tblLockerContent a INNER JOIN tblLockers b 
        ON a.locker_id = b.id_locker
        WHERE a.department_id = $depa_id AND b.in_out = '$bandeja'
        ORDER BY a.id_content DESC;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function get_history_locker($con, $locker_id, $filters = []) {
    try {
      $start_date = $filters['start'] ?? HandleDates::prev_date(1);
      $end_date = $filters['end'] ?? date('Y-m-d');
      $where = "WHERE a.locker_id = $locker_id AND a.received_at BETWEEN '" . $start_date . "T00:00:00.000' AND '" . $end_date . "T23:59:59.000'";
      $sql = "SELECT a.*, b.locker_number, b.locker_status FROM tblLockerContent a INNER JOIN tblLockers b 
      ON a.locker_id = b.id_locker $where ORDER BY a.id_content DESC";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Devuelve el ultimo registro en el casillero
   * @param PDO $con
   * @param int $locker_id
   * @return array
   */
  public static function last($con, $locker_id) {
    try {
      $sql = "SELECT TOP 1 a.*, b.locker_number, b.locker_status, c.first_name, c.last_name, c.username, c.role, c.cellphone, c.gender, c.status, d.* FROM tblLockerContent a INNER JOIN tblLockers b 
      ON a.locker_id = b.id_locker 
      LEFT JOIN tblUsers c ON c.id_user = a.user_id_target
      LEFT JOIN tblDepartments d ON d.id_department = a.department_id
      WHERE a.locker_id = $locker_id ORDER BY a.id_content DESC";
      $stmt = $con->prepare($sql);
      $stmt->execute([$locker_id]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Obtener contenido con lockers
   * @param PDO $con
   * @param string $where Es el contenido WHERE usando alias (a) con content y (b) con locker
   * @return mixed
   */
  public static function content_with_content($con, $where = "", $order = "") {
    try {
      $sql = "SELECT a.*, b.locker_number, b.type FROM tblLockerContent a INNER JOIN tblLocker b ON a.locker_id = b.id_locker $where $order";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
