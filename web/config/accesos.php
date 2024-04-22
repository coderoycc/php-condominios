<?php

namespace App\Config;

use App\Config\Database;

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
  public static function setAccesos($pin) { // seteamos las cookies para un nuevo ingreso login
    $empresa = self::getCredentialsEmp($pin, 1);
    if ($empresa) {
      $_SESSION['base'] = $empresa['base'];
      $_SESSION['dominio'] = json_encode($empresa['permisos']);
      $_SESSION['permisos'] = $empresa['permisos'];
      session_write_close();
      setcookie('base', $empresa['base'], time() + 64800, '/', false);
      setcookie('permisos', json_encode($empresa['permisos']), time() + 64800, '/', false);
      setcookie('_emp', json_encode(array('dominio' => $empresa['dominio'], 'nombre' => $empresa['nombre'], 'digest' => $empresa['digest'])), time() + 64800, '/', false);
      return 1;
    } else { // no existe el PIN
      return -1;
    }
  }
  public static function getPermisosCookies() {
    return $_COOKIE['permisos'];
  }

  public static function getNombresCookies() {
    return json_decode($_COOKIE['_emp']);
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
    if (isset($_COOKIE['base'])) {
      // echo 'EXISTE BASE?????? ' . $_COOKIE['base'];
      $base = $_COOKIE['base'];
    } else if (isset($_SESSION['base'])) {
      $base = $_SESSION['base'];
    } else {
      $base = null;
    }
    return $base;
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
}
