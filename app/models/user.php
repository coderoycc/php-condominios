<?php // propietario -> user principal
namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;
use PDO;

use function App\Utils\directorio_publico_condominio;
use function App\Utils\directory;

class User {
  private $con;
  public int $id_user;
  public string $first_name;
  public string $last_name;
  public string $username;
  public string $role;
  public string $password;
  public string $device_id;
  public string $created_at;
  public string $cellphone;
  public string $gender;
  public int $status;
  public string $photo;
  public object $suscription;

  // public string $color; // color de menu
  public function __construct($db = null, $id_user = null) {
    $this->objectNull();
    if ($db) {
      $this->con = $db;
      if ($id_user != null) {
        $sql = "SELECT * FROM tblUsers WHERE id_user = :id_user";
        $stmt = $this->con->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        $row = $stmt->fetch();
        if ($row) {
          $this->load($row);
        }
      }
    }
  }

  public function objectNull() {
    $this->id_user = 0;
    $this->first_name = '';
    $this->last_name = '';
    $this->username = '';
    $this->role = '';
    $this->password = '';
    $this->device_id = '';
    $this->created_at = '';
    $this->cellphone = '';
    $this->gender = '';
    $this->status = 0;
    $this->photo = '';
  }
  public function resetPass() {
    if ($this->con == null)
      return false;
    try {
      $sql = "UPDATE tblUsers SET password = :password WHERE id_user = :id_user";
      $stmt = $this->con->prepare($sql);
      $pass = hash('sha256', $this->username);
      return $stmt->execute(['password' => $pass, 'id_user' => $this->id_user]);
    } catch (\Throwable $th) {
      return false;
    }
  }
  public function newPass($newPass) { /// cambio de password
    if ($this->con == null)
      return false;
    try {
      $sql = "UPDATE tblUsers SET password = :password WHERE id_user = :id_user";
      $stmt = $this->con->prepare($sql);
      $pass = hash('sha256', $newPass);
      return $stmt->execute(['password' => $pass, 'id_user' => $this->id_user]);
    } catch (\Throwable $th) {
      return false;
    }
  }
  public function save() {
    if ($this->con == null)
      return -1;
    try {
      $resp = 0;
      $this->con->beginTransaction();
      if ($this->id_user == 0) { //insert
        $sql = "INSERT INTO tblUsers (username, first_name, last_name, role, password, device_id, cellphone, gender, status) VALUES (:user, :first_name, :last_name, :role, :password, :device_id, :cellphone, :gender, :status)";
        $params = ['user' => $this->username, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'role' => $this->role, 'password' => $this->password, 'device_id' => $this->device_id, 'cellphone' => $this->cellphone, 'gender' => $this->gender, 'status' => $this->status];
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->con->commit();
          $this->id_user = $this->con->lastInsertId();
          $resp = $this->id_user;
        } else {
          $resp = -1;
          $this->con->rollBack();
        }
      } else { // update
        $sql = "UPDATE tblUsers SET username = :username, first_name = :first_name, last_name = :last_name, device_id = :device_id, role = :role, cellphone = :cellphone, gender = :gender, status = :status, photo = :photo WHERE id_user = :id_user";
        $params = ['username' => $this->username, 'first_name' => $this->first_name, 'last_name' => $this->last_name, 'device_id' => $this->device_id, 'role' => $this->role, 'cellphone' => $this->cellphone, 'gender' => $this->gender, 'status' => $this->status, 'photo' => $this->photo, 'id_user' => $this->id_user];
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->con->commit();
          $resp = $this->id_user;
        } else {
          $this->con->rollBack();
          $resp = -1;
        }
      }
      return $resp;
    } catch (\Throwable $th) {
      print_r($th);
      $this->con->rollBack();
      return -1;
    }
  }

  public function addphoto($file, $pin, $condominio_name) {
    if ($this->con) {
      $prefix = 'photos';
      $ruta_base = directorio_publico_condominio($pin, $prefix);
      // guardar el archivo
      $nombre_archivo = 'U' . $this->id_user . '_' . date('Ymd_Hi');
      $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
      $ruta_archivo = $ruta_base . '/' . $nombre_archivo . '.' . $extension;
      $tmp_nombre = $file['tmp_name'];
      if (move_uploaded_file($tmp_nombre, $ruta_archivo)) {
        $base_condominio = directory($condominio_name); // nombre de condominio
        $this->photo = $base_condominio . '/' . $prefix . '/' . $nombre_archivo . '.' . $extension;
        $this->save();
        return true;
      }
    }
    return false;
  }
  public function load($row) {
    $this->id_user = $row['id_user'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'] ?? '';
    $this->username = $row['username'];
    $this->role = $row['role'];
    $this->password = $row['password'];
    $this->device_id = $row['device_id'] ?? '';
    $this->created_at = $row['created_at'];
    $this->gender = $row['gender'] ?? 'O';
    $this->cellphone = $row['cellphone'] ?? '000';
    $this->status = $row['status'] ?? 0;
    $this->photo = $row['photo'] ?? '';
  }
  public function delete() {
    if ($this->con == null)
      return false;
    try {
      $this->con->beginTransaction();
      $sql = "UPDATE tblUsers SET status = 0 WHERE id_user = :id_user";
      $stmt = $this->con->prepare($sql);
      $stmt->execute(['id_user' => $this->id_user]);
      $this->con->commit();
      return 1;
    } catch (\Throwable $th) {
      print_r($th);
      $this->con->rollBack();
      return -1;
    }
  }

  public static function usernameExist($user, $pin = null, $where = ''): bool {
    if ($pin) {
      $con = Database::getInstanceByPin($pin);
      $sql = "SELECT * FROM tblUsers WHERE (username = ? OR cellphone = ?) $where;";
      $stmt = $con->prepare($sql);
      $stmt->execute([$user, $user]);
      $row = $stmt->fetch();
      if ($row) {
        return true;
      } else {
        return false;
      }
    } else return false;
  }
  /**
   * Metodo para loggear a un usuario
   * @param string $user_login
   * @param string $pass
   * @param PDO $con
   * @return \App\Models\User
   */
  public static function exist($user_login, $pass, $con): User {
    $user = new User($con, null);
    if ($con) {
      $sql = "SELECT * FROM tblUsers a LEFT JOIN tblResidents b ON a.id_user = b.user_id 
        WHERE a.username = ? AND a.password = ? AND a.status = 1;";
      $passHash = hash('sha256', $pass);
      $stmt = $con->prepare($sql);
      $stmt->execute([$user_login, $passHash]);
      $row = $stmt->fetch();
      if ($row) {
        if ($row['role'] == 'resident') {
          $user = new Resident($con, null);
          $user->load($row);
        } else {
          $user->load($row);
        }
        // unset($user->con);
        return $user;
      } else {
        return $user;
      }
    } else return $user;
  }
  public static function all_users($con) {
    $res = [];
    try {
      $sql = "SELECT * FROM tblUsers WHERE role IN ('admin','conserje') AND status = 1;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return $res;
  }
  public static function all_residents($con, $params = []) {
    $res = [];
    try {
      $sql = "SELECT * FROM tblUsers a LEFT JOIN tblResidents b ON a.id_user = b.user_id LEFT JOIN tblDepartments c ON b.department_id = c.id_department WHERE a.role = 'resident'";

      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      //throw $th;
      var_dump($th);
    }
    return $res;
  }
}
