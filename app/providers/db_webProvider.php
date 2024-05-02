<?php
namespace App\Providers;
use App\Config\Database;
use App\Models\User;
class DBWebProvider{
  public static function session_exists(): bool{
    if(isset($_SESSION['user']) && isset($_SESSION['credentials'])){
      return true;
    }
    return false;
  }
  public static function start_session($user, $condominio){
    $_SESSION['user'] = json_encode($user);
    $_SESSION['credentials'] = json_encode($condominio);
    session_write_close();
  }
  public static function session_end(){
    session_unset();
    session_destroy();
  }

  public static function session_get_user(){}

  public static function session_get_condominio(){
    return json_decode($_SESSION['credentials']);
  }
  public static function getSessionDataDB(){
    $condominio = json_decode($_SESSION['credentials']);
    if(isset($condominio->dbname)){
      $con = Database::getInstanceX($condominio->dbname);
      return $con;
    }else return null;
  }
}