<?php

namespace App\Utils\Multitenant;

use App\Config\Database;
use PDO;
use Throwable;

/**
 * Ayuda a crear nuevo condominio y manejar eventos
 */
class Manager {
  /**
   * @param mixed $name Nombre del condominio
   * @param mixed $pin Pin del condominio
   * @param mixed $address direccion del condominio
   * @param mixed $city Ciudad del condominio
   * @param mixed $country Pais del condominio, por defecto Bolivia
   * @return array
   */
  public static function create($name, $pin, $address, $city = '', $country = 'Bolivia', $enable_qr = 0) {
    if (self::pin_exist($pin))  return ['state' => false, 'message' => 'El PIN ya existe'];
    $response = ['state' => false, 'message' => ''];
    try {
      $dbname = strtolower($name);
      $dbname = trim($dbname);
      $dbname = str_replace('  ', ' ', $dbname);
      $dbname = str_replace(' ', '_', $dbname);
      $res = self::create_database($dbname);
      if ($res) {
        $estructure = self::create_structure($dbname);
        if ($estructure) {
          $master = self::add_row_master($dbname, $name, $pin, $address, $city, $country);
          if ($master) {
            $response['state'] = true;
            $response['message'] = 'Condominio creado correctamente';
          } else
            $response['message'] = 'No se pudo crear la estructura de la base de datos';
        } else
          $response['message'] = 'No se pudo crear la estructura de la base de datos';
      } else $response['message'] = 'No se pudo crear la base de datos';
    } catch (Throwable $th) {
      $response['state'] = false;
      $response['message'] = $th->getMessage();
    }
    return $response;
  }
  private static function create_database($dbname) {
    try {
      $con = Database::master_instance();
      $sql = "CREATE DATABASE $dbname COLLATE Modern_Spanish_CI_AS";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      return $res;
    } catch (Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  private static function create_structure($database) {
    try {
      $sql = "USE $database; ";
      $sqlContent = file_get_contents(__DIR__ . '/../queries/estructure.sql');

      // Eliminar las declaraciones 'GO'
      $sqlContent = preg_replace('/\bGO\b/i', ';', $sqlContent);
      // dividido a dos porque no acepta varias lineas
      $sql_1 = explode('--**separation', $sqlContent);

      $con = Database::master_instance();
      $stmt = $con->prepare($sql . $sql_1[0]);
      $stmt->execute();

      $stmt2 = $con->prepare($sql . $sql_1[1]);
      $res = $stmt2->execute();
      return $res;
    } catch (Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  private static function add_row_master($database, $name, $pin, $address, $city = '', $country = 'Bolivia') {
    try {
      $con = Database::master_instance();
      $sql = "INSERT INTO [condominios_master].[dbo].[tblCondominiosData](name, pin, dbname, address, city, country) VALUES('$name', '$pin', '$database', '$address', '$city', '$country');";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      return $res;
    } catch (Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  private static function pin_exist($pin) {
    try {
      $con = Database::master_instance();
      $sql = "SELECT * FROM [condominios_master].[dbo].[tblCondominiosData] WHERE pin = '$pin';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return count($res) > 0;
    } catch (Throwable $th) {
      var_dump($th);
    }
    return true;
  }
}
