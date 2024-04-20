<?php

namespace App\Config;

use App\Config\Accesos;

class Database {
  private static $serverName = "";
  private static $username = "";
  private static $password = "";
  private static $con = null;
  private function __construct() {
  }
  public static function getInstace() {
    $base = Accesos::base();
    if ($base == null) {
      return null;
    }
    $databaseName = $base;
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=$databaseName;Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      // print_r($e);
      die("Error de conexión: " . $e->getMessage());
    }
    return self::$con;
  }
  public static function getInstaceEmpresa() {
    $databaseName = 'empresas';
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=$databaseName;Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      die("Error de conexión: " . $e->getMessage());
    }
    return self::$con;
  }
  public static function getInstanceX($databaseName) {
    try {
      self::$con = new \PDO("sqlsrv:Server=" . self::$serverName . ";Database=$databaseName;Encrypt=0;TrustServerCertificate=1", self::$username, self::$password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      die("Error de conexión: " . $e->getMessage());
    }
    return self::$con;
  }
}
