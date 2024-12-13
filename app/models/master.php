<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Master {
  public static function get_condominios($where = '', $fields = 'name, pin') {
    $con = Database::getInstaceCondominiosExterno();
    $stmt = $con->prepare("SELECT $fields FROM tblCondominiosData $where;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
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
  public static function get_countries($filters) {
    $s = $filters['search'] ?? '';
    $con = Database::getInstaceCondominiosExterno();
    if ($con) {
      $sql = "SELECT name, cca2, cca3, name_official, name_esp FROM tblCountries WHERE name_esp LIKE '%$s%' OR name_official LIKE '%$s%';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
  }
  /**
   * Retorna el numero de celular de soporte
   * @return string
   */
  public static function get_support_phone() {
    $con = Database::getInstaceCondominiosExterno();
    if ($con) {
      $sql = "SELECT TOP 1 support_phone FROM tblConfig;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $telefono = $stmt->fetch(PDO::FETCH_ASSOC)['support_phone'] ?? '';
      return $telefono;
    }
    return '';
  }

  /**
   * Devuelve los datos de la tabla config de condominios
   */
  public static function config_data() {
    $con = Database::getInstaceCondominios();
    if ($con) {
      $sql = "SELECT * FROM tblConfig;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
  }
}
