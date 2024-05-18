<?php

namespace App\Providers;

use App\Config\Database;
use App\Models\Resident;
use App\Models\Subscription;

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
    $condominio = json_decode($_SESSION['credentials']);
    if (isset($condominio->dbname)) {
      $con = Database::getInstanceX($condominio->dbname);
      return $con;
    } else return null;
  }
}

class DBAppProvider {
  public static function exist() {
    if ($GLOBALS['payload'] == null)
      return false;
    return true;
  }
  public static function get_resident(): Resident {
    $con = DBAppProvider::get_conecction();
    $id_user = $GLOBALS['payload']['user_id'];
    $resident = new Resident($con, $id_user);
    return $resident;
  }
  public static function get_conecction() {
    if (self::exist()) {
      $con = Database::getInstanceX(self::get_db_name());
      return $con;
    }
    return null;
  }
  public static function get_db_name(): string {
    $decode = base64_decode($GLOBALS['payload']['credential']);
    $db_name = base64_decode($decode);
    return $db_name;
  }
  public static function sub($target = 'id') {
    $sub_object = base64_decode($GLOBALS['payload']['us_su']);
    $sub_object = base64_decode(json_decode($sub_object, true));
    return $sub_object[$target];
  }
  public static function get_sub(): Subscription {
    $sub_object = base64_decode($GLOBALS['payload']['us_su']);
    $sub_object = base64_decode(json_decode($sub_object, true));
    $con = self::get_conecction();
    $sub = new Subscription($con, $sub_object['id']);
    return $sub;
  }
}
