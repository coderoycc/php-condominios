<?php // propietario -> usuario principal
namespace App\Models;

use App\Config\Database;

class User {
  public int $idUsuario;
  public string $nombre;
  public string $usuario;
  public string $rol;
  public string $password;
  public string $color; // color de menu
  public function __construct($idUsuario = null) {
    if ($idUsuario != null) {
      $con = Database::getInstace();
      $sql = "SELECT u.*, l.lugar FROM tblUsuario u JOIN tblLugar l ON u.idLugar = l.idLugar WHERE u.idUsuario = :idUsuario";
      $stmt = $con->prepare($sql);
      $stmt->execute(['idUsuario' => $idUsuario]);
      $row = $stmt->fetch();
      if ($row) {
        $this->load($row);
      } else {
        $this->objectNull();
      }
    } else {
      $this->objectNull();
    }
  }

  public function objectNull() {
    $this->idUsuario = 0;
    $this->nombre = 'Invitado';
    $this->usuario = '';
    $this->rol = '';
    $this->password = '';
    $this->color = '#212529';
  }
  public function resetPass() {
    try {
      $con = Database::getInstace();
      $sql = "UPDATE tblUsuario SET password = :password WHERE idUsuario = :idUsuario";
      $stmt = $con->prepare($sql);
      $pass = hash('sha256', $this->usuario);
      return $stmt->execute(['password' => $pass, 'idUsuario' => $this->idUsuario]);
    } catch (\Throwable $th) {
      return -1;
    }
  }
  public function newPass($newPass) { /// cambio de password
    try {
      $con = Database::getInstace();
      $sql = "UPDATE tblUsuario SET password = :password WHERE idUsuario = :idUsuario";
      $stmt = $con->prepare($sql);
      $pass = hash('sha256', $newPass);
      $stmt->execute(['password' => $pass, 'idUsuario' => $this->idUsuario]);
      return 1;
    } catch (\Throwable $th) {
      return -1;
    }
  }
  public function save() {
    try {
      $con = Database::getInstace();
      if ($this->idUsuario == 0) { //insert
        $sql = "INSERT INTO tblUsuario (usuario, nombre, rol, idLugar, color, password) VALUES (:usuario, :nombre, :rol, :idLugar, :color, :password)";
        $params = ['usuario' => $this->usuario, 'nombre' => $this->nombre, 'rol' => $this->rol, 'color' => '#212529', 'password' => $this->password];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->idUsuario = $con->lastInsertId();
          $res = $this->idUsuario;
        }
      } else { // update
        $sql = "UPDATE tblUsuario SET usuario = :usuario, nombre = :nombre, rol = :rol, color = :color, idLugar = :idLugar WHERE idUsuario = :idUsuario";
        $params = ['usuario' => $this->usuario, 'nombre' => $this->nombre, 'rol' => $this->rol, 'color' => $this->color, 'idUsuario' => $this->idUsuario];
        $stmt = $con->prepare($sql);
        $stmt->execute($params);
        $res = 1;
      }
      return $res;
    } catch (\Throwable $th) {
      print_r($th);
      return -1;
    }
  }

  public function load($row) {
    $this->idUsuario = $row['idUsuario'];
    $this->nombre = $row['nombre'];
    $this->usuario = $row['usuario'];
    $this->rol = $row['rol'];
    $this->color = $row['color'];
    $this->password = $row['password'];
  }
  public function delete() {
    try {
      $con = Database::getInstace();
      $sql = "DELETE FROM tblUsuario WHERE idUsuario = :idUsuario";
      $stmt = $con->prepare($sql);
      $stmt->execute(['idUsuario' => $this->idUsuario]);
      return 1;
    } catch (\Throwable $th) {
      return -1;
    }
  }
  public static function exist($usuario, $pass, $pin): User {
    $user = new User();
    $con = Database::getInstanceX($pin);
    if($con){
      $sql = "SELECT * FROM tblUsuraio WHERE usuario = :usuario AND password = :password";
      $passHash = hash('sha256', $pass);
      $stmt = $con->prepare($sql);
      $stmt->execute(['usuario' => $usuario, 'password' => $passHash]);
      $row = $stmt->fetch();
      if ($row) {
        $user->load($row);
        return $user;
      } else {
        return $user;
      }
    }else return $user;
  }

  public static function getAllUsers() {
    $con = Database::getInstace();
    $sql = "SELECT u.*, l.lugar FROM tblUsuario u JOIN tblLugar l ON u.idLugar = l.idLugar";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
  }
}
