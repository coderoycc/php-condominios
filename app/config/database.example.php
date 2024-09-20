<?php

namespace App\Config;

use App\Config\Accesos;
use Helpers\Resources\Response;
use Throwable;

class Database {
  private static $serverName = "localhost";
  private static $username = "sa2";
  private static $password = "saza";
  private static $con = null;
  private function __construct() {
  }
  public static function getInstance() {
    $base = Accesos::base();
    if ($base == null) {
      return Response::error_json(['message' => 'Error  DB obtener instancia DB']);
    }
    $databaseName = $base;
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=$databaseName;Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      // print_r($e);
      Response::error_json(['message' => '¡Error DB! Instance', 'data' => $e], 500);
    }
    return self::$con;
  }
  public static function getInstaceCondominios() {
    $databaseName = 'condominios_master';
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=" . $databaseName . ";Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      Response::error_json(['message' => '¡Error DB! Instance master', 'data' => $e], 500);
    }
    return self::$con;
  }
  public static function master_instance() {
    try {
      // Removemos el parámetro Database de la cadena de conexión
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
    }
    return self::$con;
  }
  public static function getInstaceCondominiosExterno() {
    $databaseName = 'condominios_master';
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=" . $databaseName . ";Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
    }
    return self::$con;
  }

  public static function getInstanceByPin($pin) {
    try {
      $con = self::getInstaceCondominios();
      $sql = "SELECT * FROM tblCondominiosData WHERE pin = '$pin';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetch();

      if (isset($res['dbname'])) {
        return self::getInstanceX($res['dbname']);
      } else {
        Response::error_json(['message' => '¡Error DB! Pin no válido'], 400);
      }
    } catch (\Throwable $th) {
      Response::error_json(['message' => '¡Error DB! Instance PIN', 'details' => $th->getMessage()], 500);
    }
  }
  public static function getInstanceByPinExterno($pin) {
    try {
      $con = self::getInstaceCondominiosExterno();
      $sql = "SELECT * FROM tblCondominiosData WHERE pin = '$pin';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetch();

      if (isset($res['dbname'])) {
        return self::getInstanceX($res['dbname']);
      } else {
        return null;
      }
    } catch (Throwable $th) {
      return null;
    }
  }
  public static function getInstanceX($databaseName) {
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=$databaseName;Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      Response::error_json(['message' => "¡Error! Instance ##$databaseName##"], 500);
    }
    return self::$con;
  }
}
