<?php

namespace App\Providers;

use App\Config\Database;
use App\Models\Resident;
use App\Models\Subscription;
use PDO;

class DBWebProvider {
  public static function session_exists(): bool {
    if (isset($_SESSION['user']) && isset($_SESSION['credentials'])) {
      return true;
    }
    return false;
  }
  public static function start_session($user, $condominio) {
    $_SESSION['user'] = json_encode($user);
    $_SESSION['credentials'] = json_encode($condominio);
    session_write_close();
  }
  public static function session_end() {
    session_unset();
    session_destroy();
  }

  public static function session_get_user() {
  }

  public static function session_get_condominio() {
    return json_decode($_SESSION['credentials']);
  }
  public static function getSessionDataDB() {
    if (isset($_SESSION['credentials'])) {
      $condominio = json_decode($_SESSION['credentials']);
      if (isset($condominio->dbname)) {
        $con = Database::getInstanceX($condominio->dbname);
        return $con;
      } else return null;
    }
    return null;
  }
}

class DBAppProvider {
  public static function exist() {
    if ($GLOBALS['payload'] == null)
      return false;
    return true;
  }
  /**
   * Obtener el registro del residente logeado por el payload
   * @return Resident
   */
  public static function get_resident(): Resident {
    $con = DBAppProvider::get_conecction();
    $id_user = $GLOBALS['payload']['user_id'];
    $resident = new Resident($con, $id_user);
    return $resident;
  }
  /**
   * Devuelve la conexion a la base de datos usando la instancia (pin) en el token 
   * @return PDO|null
   */
  public static function get_conecction() {
    if (self::exist()) {
      $con = Database::getInstanceX(self::get_db_name());
      return $con;
    }
    return null;
  }
  /**
   * Devuelve una cadena con el nombre de la base de datos usando el payload
   * @return string
   */
  public static function get_db_name(): string {
    $decode = base64_decode($GLOBALS['payload']['credential']);
    $db_name = base64_decode($decode);
    return $db_name;
  }

  /**
   * Devuelve el objeto Subscription del residente logeado por el payload
   * @return Subscription
   */
  public static function get_sub(): Subscription {
    $sub_object = base64_decode($GLOBALS['payload']['us_su']);
    $sub_object = base64_decode($sub_object);
    $con = self::get_conecction();
    $id = str_replace("S-", "", $sub_object);
    $sub = new Subscription($con, $id);
    return $sub;
  }
  /**
   * Retorna el valor del payload de la peticion
   * @param string $key - La clave del valor que se desea obtener del payload
   * @return mixed
   */
  public static function get_payload_value($key = 'user_id') {
    $payload = $GLOBALS['payload'];
    if (isset($payload[$key])) {
      return $payload[$key];
    }
    return null;
  }
}
