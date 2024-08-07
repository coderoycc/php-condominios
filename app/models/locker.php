<?php

namespace App\Models;

use PDO;

require_once(__DIR__ . '/lockerContent.php');
class Locker {
  private $con;
  public int $id_locker;
  public int $locker_number;
  public int $locker_status;
  public string $type;
  public string $in_out; // bandeja de ENTRADA | SALIDA
  public function __construct($con = null, $id_locker = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_locker) {
        $sql = "SELECT * FROM tblLockers WHERE id_locker = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$id_locker]);
        if ($res) {
          $row = $stmt->fetch();
          if ($row)
            $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_locker = 0;
    $this->locker_number = 0;
    $this->locker_status = 0;
    $this->type = "";
    $this->in_out = "";
  }
  public function load($row) {
    $this->id_locker = $row['id_locker'];
    $this->locker_number = $row['locker_number'];
    $this->locker_status = $row['locker_status'] ?? 0;
    $this->type = $row['type'];
    $this->in_out = $row['in_out'] ?? "";
  }
  public function save() {
    if ($this->con) {
      $res = 0;
      if ($this->id_locker == 0) { //insert
        $sql = "INSERT INTO tblLockers(locker_number, type, in_out) VALUES(?,?,?);";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->type, $this->in_out]);
        if ($res) {
          $this->id_locker = $this->con->lastInsertId();
          $res = $this->id_locker;
        }
      } else { // update
        $sql = "UPDATE tblLockers SET locker_number = ?, type = ?, in_out = ? WHERE id_locker = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->type, $this->in_out, $this->id_locker]);
        if ($res)
          $res = $this->id_locker;
      }
      return $res;
    }
    return -1;
  }
  public function updateStatus($status) {
    if ($status == 0 || $status == 1) {
      $sql = "UPDATE tblLockers SET locker_status = ? WHERE id_locker = ?";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$status, $this->id_locker]);
      if ($res)
        return true;
    }
    return false;
  }
  public function message_notification() {
    $message = '';
    if ($this->in_out == 'ENTRADA') {
      $message = $this->type == "todo" ?
        'Usted acaba de recibir un pedido en el casillero Nro. ' . $this->locker_number . '. Tiene 30 min. para recogerlo.' :
        'Usted acaba de recibir correspondencia en el casillero Nro. ' . $this->locker_number;
    } else {
      $message = 'Su envio se encuentra en el casillero Nro. ' . $this->locker_number;
    }
    return $message;
  }
  public function delete() {
    if ($this->con) {
      $sql = "DELETE FROM tblLockers WHERE id_locker = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->id_locker]);
      return $res;
    }
    return false;
  }
  public function addContent($user_id, $content = "", $department_id, $received_by) {
    if ($this->con) {
      $lockerContent = new LockerContent($this->con);
      $lockerContent->locker_id = $this->id_locker;
      $lockerContent->content = $content;
      $lockerContent->user_id_target = $user_id;
      $lockerContent->department_id = $department_id;
      $lockerContent->received_by = $received_by;
      if ($lockerContent->save() > 0) {
        if ($this->type == "todo")  // actualizamos el estado a ocupado si es del tipo "todo"
          $this->updateStatus(0);
      }
      return $lockerContent;
    }
    return new LockerContent();
  }
  public static function getAll($con, $params) {
    try {
      $order_name = $params['order_name'] ?? 'locker_number';
      $order_type = $params['order_type'] ?? 'ASC';
      $in_out = $params['in_out'] ?? '';
      $sql = "SELECT * FROM tblLockers WHERE in_out LIKE '%$in_out%' ORDER BY $order_name $order_type;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      if ($res) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rows)
          return $rows;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function number_exist($con, $locker_number) {
    try {
      $sql = "SELECT * FROM tblLockers WHERE locker_number = ?;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute([$locker_number]);
      if ($res) {
        $row = $stmt->fetchAll();
        if (count($row) > 0)
          return true;
        return false;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return true; // para que no cree el locker
  }
}
