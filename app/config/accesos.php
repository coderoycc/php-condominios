<?php

namespace App\Config;

use App\Config\Database;
use Helpers\Resources\Response;
use PDO;

class Accesos {

  public static function delAccesos() {
    unset($_COOKIE['base']);
    unset($_COOKIE['permisos']);
    unset($_COOKIE['dominio']);
    unset($_SESSION['base']);
    unset($_SESSION['permisos']);
    setcookie('base', '', -1, '/', false);
    setcookie('permisos', '', -1, '/', false);
    setcookie('_emp', '', -1, '/', false);
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
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      //throw $th;
    }
    return $res;
  }
}
