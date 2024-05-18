<?php

namespace App\Models;

use App\Config\Database;

class Master {
  public static function create_ad() {
  }
  public static function create_service_name(array $data): bool {
    $con = Database::getInstaceCondominios();
    try {
      $sql = "INSERT INTO tblNombresServicios(servicio, acronimo) VALUES(?, ?);";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute([$data['name'], $data['acronym']]);
      return $res;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public static function update_service_name(array $data): bool {
    $con = Database::getInstaceCondominios();
    try {
      $sql = "UPDATE tblNombresServicios SET servicio = ?, acronimo = ? WHERE id_servicio = ?;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute([$data['name'], $data['acronym'], $data['id']]);
      return $res;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public static function delete_service_name(int $id): bool {
    $con = Database::getInstaceCondominiosExterno();
    try {
      $sql = "DELETE FROM tblNombresServicios WHERE id_servicio = ?;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute([$id]);
      return $res;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return false;
  }
  public static function get_services_names() {
    $con = Database::getInstaceCondominiosExterno();
    if ($con) {
      $sql = "SELECT * FROM tblNombresServicios;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    return [];
  }
  public static function get_ads(int $c = 1, string $type = 'image') {
  }
}
