<?php

namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;

class Condominius {
  public static function search($q = '') {
    try {
      $con = Database::getInstaceCondominios();
      $query = $con->prepare("SELECT * FROM tblCondominiosData WHERE name LIKE '%$q%' OR address LIKE '%$q%';");
      $query->execute();
      $data = $query->fetchAll(\PDO::FETCH_ASSOC);
      return $data;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
}
