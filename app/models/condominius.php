<?php
namespace App\Models;
use App\Config\Accesos;

class Condominius{
  public static function prueba(){
    $accesos = $GLOBALS['accesos'];
    var_dump($accesos);
  }
}