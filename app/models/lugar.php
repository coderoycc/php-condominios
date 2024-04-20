<?php

namespace App\Models;

use App\Config\Database;

class Lugar {

  public $idLugar;
  public $lugar;

  public $observacion;
  public function __construct($idLugar = null) {
    $con = Database::getInstace();
    if ($idLugar != null) {
      $sql = "SELECT * FROM tblLugar WHERE idLugar = $idLugar";
      $stmt = $con->prepare($sql);
      $stmt->execute([]);
      $row = $stmt->fetch(); // Utiliza fetch en lugar de fetchAll
      if ($row) {
        $this->idLugar = $row['idLugar'];
        $this->lugar = $row['lugar'];
        $this->observacion = $row['observacion'];
      } else {
        $this->objectNull();
      }
    } else {
      $this->objectNull();
    }
  }
  public function objectNull() {
    $this->idLugar = 0;
    $this->lugar = '';
    $this->observacion = '';
  }
  public function save() {
    try {
      $con = Database::getInstace();
      if ($this->idLugar == 0) {
        $sql = "INSERT INTO tblLugar (lugar) VALUES (?)";
        $stmt = $con->prepare($sql);
        $stmt->execute([$this->lugar]);
        $this->idLugar = $con->lastInsertId();
        return $this->idLugar;
      } else {
        $sql = "UPDATE tblLugar SET lugar = ? WHERE idLugar = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$this->lugar, $this->idLugar]);
        return $this->idLugar;
      }
    } catch (\Throwable $th) {
      // print_r($th);
      return -1;
    }
  }
  public function delete() {
    try {
      $con = Database::getInstace();
      $sql = "DELETE FROM tblLugar WHERE idLugar = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idLugar]);
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }

  public static function all() {
    try {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblLugar";
      $stmt = $con->prepare($sql);
      $stmt->execute([]);
      $rows = $stmt->fetchAll(); // Utiliza fetch en lugar de fetchAll
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return [];
  }
}
