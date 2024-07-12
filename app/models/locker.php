<?php

namespace App\Models;

require_once(__DIR__ . '/lockerContent.php');
class Locker {
  private $con;
  public int $id_locker;
  public int $locker_number;
  public int $locker_status;
  public string $type;
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
  }
  public function load($row) {
    $this->id_locker = $row['id_locker'];
    $this->locker_number = $row['locker_number'];
    $this->locker_status = $row['locker_status'] ?? 0;
    $this->type = $row['type'];
  }
  public function save() {
    if ($this->con) {
      $res = 0;
      if ($this->id_locker == 0) { //insert
        $sql = "INSERT INTO tblLockers(locker_number, type) VALUES(?,?);";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->type]);
        if ($res) {
          $this->id_locker = $this->con->lastInsertId();
          $res = $this->id_locker;
        }
      } else { // update
        $sql = "UPDATE tblLockers SET locker_number = ?, type = ? WHERE id_locker = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->type, $this->id_locker]);
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
  public function delete() {
    if ($this->con) {
      $sql = "DELETE FROM tblLockers WHERE id_locker = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->id_locker]);
      return $res;
    }
    return false;
  }
  public function addContent($user_id, $content = "") {
    if ($this->con) {
      $lockerContent = new LockerContent($this->con);
      $lockerContent->locker_id = $this->id_locker;
      $lockerContent->content = $content;
      $lockerContent->user_id_target = $user_id;
      $res = true;
      if ($lockerContent->save() > 0) {
        if ($this->type == "todo") { // actualizamos el estado a ocupado si es del tipo "todo"
          $res = $this->updateStatus(0);
        }
      } else {
        $res = false;
      }
      return $res;
    } else {
    }
    return false;
  }
  public static function getAll($con, $params) {
    try {
      $order_name = $params['order_name'] ?? 'id_locker';
      $order_type = $params['order_type'] ?? 'DESC';
      $sql = "SELECT * FROM tblLockers ORDER BY $order_name $order_type;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      if ($res) {
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
