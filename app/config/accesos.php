<?php

namespace App\Config;

use App\Config\Database;
use Helpers\Resources\Response;

class Accesos {
  /**
   * @param string target indica el valor para comparar en la base de datos y encontrar las credenciales
   * @param bool xPin indica si target debe ser comparado por pin o por digest << 0 = comparar por digest>> | << 1 = comparar por pin >> (Default = 0)
   */
  public static function getCredentialsEmp($target, $xPin = 0) {
    try {
      // $con = Database::getInstaceEmpresa();
      // $sql = "SELECT * FROM tblEmpresasData";
      // $sql .= $xPin ? " WHERE pin = '$target';" : " WHERE digest = '$target';";
      // $stmt = $con->prepare($sql);
      // $stmt->execute();
      // return $stmt->fetch();
      $empresas = [
        'bolivar' => ['base' => 'correspondencia', 'dominio' => 'bolivar', 'permisos' => [], 'digest' => '5932b1a8b1d0dd9fc4a5c10d6b47e3016ad0f6e1078f3d5f0ce6fe38bfc20065', 'nombre' => 'BOLIVAR SRL.'],
        // 'bolivar' => ['base' => 'correspondencia_bolivar', 'dominio' => 'bolivar', 'permisos' => [], 'digest' => '5932b1a8b1d0dd9fc4a5c10d6b47e3016ad0f6e1078f3d5f0ce6fe38bfc20065', 'nombre' => 'BOLIVAR SRL.'],
        'illimani' => ['base' => 'correpondencia2', 'dominio' => 'illimani', 'permisos' => []],
      ];
      return $empresas['bolivar'];
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return [];
  }
  public static function delAccesos() {
    unset($_COOKIE['base']);
    unset($_COOKIE['permisos']);
    unset($_COOKIE['dominio']);
    unset($_SESSION['base']);
    unset($_SESSION['permisos']);
    setcookie('base', null, -1, '/', false);
    setcookie('permisos', null, -1, '/', false);
    setcookie('_emp', null, -1, '/', false);
    session_destroy();
  }
  public static function base() {
    if (isset($GLOBALS['payload'])) {
      $data = $GLOBALS['payload'];
      if (isset($data['credential'])) {
        return $data['credential'];
      } else {
        Response::error_json(['message' => 'Ocurrió un error, subservicio base'], 500);
      }
    } else if (isset($_SESSION['credentials'])) {
      $data = json_decode($_SESSION['credentials']);
      if (isset($data->dbname)) {
        return $_SESSION['credentials'];
      } else
        Response::error_json(['message' => 'Credenciales necesarios']);
    } else {
      Response::error_json(['message' => 'Ocurrió un error, conexión con un subservicio base'], 500);
    }
  }
  public static function dominio() {
    if (isset($_COOKIE['dominio'])) {
      $domain = $_COOKIE['dominio'];
    } else if (isset($_SESSION['dominio'])) {
      $domain = $_SESSION['dominio'];
    } else {
      $domain = null;
    }
    return $domain;
  }

  public static function getCondominio($pin) {
    $res = [];
    try {
      $con = Database::getInstaceCondominios();
      $sql = "SELECT * FROM tblCondominiosData WHERE pin = '$pin';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      //throw $th;
    }
    return $res;
  }
}
