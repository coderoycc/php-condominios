<?php

namespace App\Models;

use App\Config\Database;
use PDO;

use function App\Providers\logger;

class Condominius {
  public static function search($q = '') {
    try {
      $con = Database::getInstaceCondominios();
      $query = $con->prepare("SELECT * FROM tblCondominiosData WHERE name LIKE '%$q%' OR address LIKE '%$q%';");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
    } catch (\Throwable $th) {
      //throw $th;
      logger()->error($th);
    }
    return [];
  }
  public static function all() {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCondominiosData;";
      $query = $con->prepare($sql);
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
    } catch (\Throwable $th) {
      logger()->error($th);
      throw $th;
    }
  }
  public static function name_exist($name) {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCondominiosData WHERE name = '$name';";
      $query = $con->prepare($sql);
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      if (count($data) > 0) {
        return true;
      } else {
        return false;
      }
    } catch (\Throwable $th) {
      logger()->error($th);
      throw $th;
    }
  }
  public static function pin_exist($pin) {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCondominiosData WHERE pin = '$pin';";
      $query = $con->prepare($sql);
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      if (count($data) > 0) {
        return true;
      } else {
        return false;
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
