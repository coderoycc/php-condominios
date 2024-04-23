<?php // propietario -> user principal
namespace App\Models;

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
  public string $genre;

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
  public function save() {
    try {
      $con = Database::getInstace();
      if ($this->id_user == 0) { //insert
        $sql = "INSERT INTO tblUsers (user, first_name, last_name, role, password, device_id, cellphone) VALUES (:user, :first_name, :last_name, :role, :password, :device_id, :cellphone)";
        $params = ['user' => $this->user, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'role' => $this->role, 'password' => $this->password, 'device_id' => $this->device_id, 'cellphone' => $this->cellphone];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->id_user = $con->lastInsertId();
          $res = $this->id_user;
        }
      } else { // update
        $sql = "UPDATE tblUsers SET user = :user, first_name = :first_name, last_name = :last_name, device_id = :device_id, role = :role, cellphone = :cellphone WHERE id_user = :id_user";
        $params = ['user' => $this->user, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'device_id' => $this->device_id, 'role' => $this->role, 'cellphone' => $this->cellphone, 'id_user' => $this->id_user];
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
    $this->id_user = $row['id_user'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->user = $row['user'];
    $this->role = $row['role'];
    $this->password = $row['password'];
    $this->device_id = $row['device_id'];
    $this->created_at = $row['created_at'];
  }
  public function delete() {
    try {
      $con = Database::getInstace();
      $sql = "DELETE FROM tblUsers WHERE id_user = :id_user";
      $stmt = $con->prepare($sql);
      $stmt->execute(['id_user' => $this->id_user]);
      return 1;
    } catch (\Throwable $th) {
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
      $sql = "SELECT * FROM tblUsers WHERE user = :user AND password = :password";
      $passHash = hash('sha256', $pass);
      $stmt = $con->prepare($sql);
      $stmt->execute(['user' => $user_login, 'password' => $passHash]);
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
