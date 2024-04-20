<?php // motivos de los proyectos
namespace App\Models;

use App\Config\Database;

class Motivo {
  public static function getList($cadena) {
    $sql = "SELECT * FROM tblMotivo WHERE motivo LIKE '%$cadena%'";
    $con = Database::getInstace();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
