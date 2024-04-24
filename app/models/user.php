<?php // propietario -> user principal
namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;

class User {
  public int $id_user;
  public string $first_name;
  public string $last_name;
  public string $user;
  public string $role;
  public string $password;
  public string $device_id;
  public string $created_at;
  public string $cellphone;
  public string $gender;
  public int $status;
  public object $suscription;

  // public string $color; // color de menu
  public function __construct($id_user = null) {
    if ($id_user != null) {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblUsers WHERE id_user = :id_user";
      $stmt = $con->prepare($sql);
      $stmt->execute(['id_user' => $id_user]);
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
    $this->id_user = 0;
    $this->first_name = '';
    $this->last_name = '';
    $this->user = '';
    $this->role = '';
    $this->password = '';
    $this->device_id = '';
    $this->created_at = '';
    $this->cellphone = '';
    $this->gender = '';
    $this->status = 0;
  }
  public function resetPass() {
    try {
      $con = Database::getInstace();
      $sql = "UPDATE tblUsers SET password = :password WHERE id_user = :id_user";
      $stmt = $con->prepare($sql);
      $pass = hash('sha256', $this->user);
      return $stmt->execute(['password' => $pass, 'id_user' => $this->id_user]);
    } catch (\Throwable $th) {
      return -1;
    }
  }
  public function newPass($newPass) { /// cambio de password
    try {
      $con = Database::getInstace();
      $sql = "UPDATE tblUsers SET password = :password WHERE id_user = :id_user";
      $stmt = $con->prepare($sql);
      $pass = hash('sha256', $newPass);
      $stmt->execute(['password' => $pass, 'id_user' => $this->id_user]);
      return 1;
    } catch (\Throwable $th) {
      return -1;
    }
  }
  public function save($pin = null) {
    try {
      $resp = 0;
      if ($pin != null) $con = Database::getInstanceByPin($pin);
      else $con = Database::getInstace();
      $con->beginTransaction();
      if ($this->id_user == 0) { //insert
        $sql = "INSERT INTO tblUsers (user, first_name, last_name, role, password, device_id, cellphone, gender, status) VALUES (:user, :first_name, :last_name, :role, :password, :device_id, :cellphone, :gender, :status)";
        $params = ['user' => $this->user, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'role' => $this->role, 'password' => $this->password, 'device_id' => $this->device_id, 'cellphone' => $this->cellphone, 'gender' => $this->gender, 'status' => $this->status];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $con->commit();
          $this->id_user = $con->lastInsertId();
          $resp = $this->id_user;
        } else {
          $resp = -1;
          $con->rollBack();
        }
      } else { // update
        $sql = "UPDATE tblUsers SET user = :user, first_name = :first_name, last_name = :last_name, device_id = :device_id, role = :role, cellphone = :cellphone, gender = :gender, status = :status WHERE id_user = :id_user";
        $params = ['user' => $this->user, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'device_id' => $this->device_id, 'role' => $this->role, 'cellphone' => $this->cellphone, 'gender' => $this->gender, 'status' => $this->status, 'id_user' => $this->id_user];
        $stmt = $con->prepare($sql);
        if ($stmt->execute($params)) {
          $con->commit();
          $resp = $this->id_user;
        } else {
          $con->rollBack();
          $resp = -1;
        }
      }
      return $resp;
    } catch (\Throwable $th) {
      print_r($th);
      $con->rollBack();
      return -1;
    }
  }

  public function load($row) {
    $this->id_user = $row['id_user'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'] ?? '';
    $this->user = $row['user'];
    $this->role = $row['role'];
    $this->password = $row['password'];
    $this->device_id = $row['device_id'] ?? '';
    $this->created_at = $row['created_at'];
    $this->gender = $row['gender'] ?? 'O';
    $this->cellphone = $row['cellphone'] ?? '000';
    $this->status = $row['status'] ?? 0;
  }
  public function delete() {
    try {
      $con = Database::getInstace();
      $con->beginTransaction();
      $sql = "DELETE FROM tblUsers WHERE id_user = :id_user";
      $stmt = $con->prepare($sql);
      $stmt->execute(['id_user' => $this->id_user]);
      $con->commit();
      return 1;
    } catch (\Throwable $th) {
      $con->rollBack();
      return -1;
    }
  }

  public function verifySubscription() {
    if ($this->id_user == 0)
      return null;
    $subscriptionInfo = [];
    try {
      if ($this->role == 'RESIDENT') {
      } else {
        $subscriptionInfo['expiration_date'] = '2080-12-31';
        $subscriptionInfo['subscription_type'] = 'ADMIN';
        $subscriptionInfo['quantity'] = 1;
      }
      return $subscriptionInfo;
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }

  public static function exist($user_login, $pass, $pin): User {
    $user = new User();
    $con = Database::getInstanceX($pin);
    if ($con) {
      $sql = "SELECT * FROM tblUsers WHERE tblUsers.[user] = ? AND tblUsers.[password] = ?";
      $passHash = hash('sha256', $pass);
      $stmt = $con->prepare($sql);
      $stmt->execute([$user_login, $passHash]);
      $row = $stmt->fetch();
      if ($row) {
        $user->load($row);
        return $user;
      } else {
        return $user;
      }
    } else return $user;
  }
}
