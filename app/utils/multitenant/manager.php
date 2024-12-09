<?php

namespace App\Utils\Multitenant;

use App\Config\Database;
use App\Models\Condominius;
use Throwable;

use function App\Providers\logger;

/**
 * Ayuda a crear nuevo condominio y manejar eventos
 */
class Manager {
  /**
   * Devuelve el nuevo nombre de la base de datos de acuerdo al nombre del condominio
   * @param string $name
   * @return string
   */
  public static function dbname($name) {
    $dbname = strtolower($name);
    $dbname = trim($dbname);
    $dbname = str_replace('  ', ' ', $dbname);
    $dbname = str_replace(' ', '_', $dbname);
    $dbname = "condominio_$dbname";
    return $dbname;
  }
  /**
   * @param mixed $name Nombre del condominio
   * @param mixed $pin Pin del condominio
   * @param mixed $address direccion del condominio
   * @param mixed $city Ciudad del condominio
   * @param mixed $country Pais del condominio, por defecto Bolivia
   * @return array
   */
  public static function create($name, $pin, $address, $city = '', $country = 'Bolivia', $enable_qr = 0) {
    $response = ['state' => false, 'message' => ''];
    try {
      $dbname = self::dbname($name);
      $res = self::create_database($dbname);
      if ($res) {
        $estructure = self::create_structure($dbname);
        if ($estructure) {
          self::insert_default_data($dbname);
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
      logger()->error($th);
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
      logger()->error($th);
    }
    return false;
  }
  /**
   * Agrega datos por defecto a la base de datos (tipo de suscripcion y usuario)
   * @return void
   */
  private static function insert_default_data($dbname) {
    try {
      $condominios = Condominius::all();
      $condominio = $condominios[0];
      $sql = "USE [$dbname]; 
      SET IDENTITY_INSERT [tblSubscriptionType] ON;
      INSERT INTO [tblSubscriptionType] (id_subscription_type, name, tag, see_lockers, see_services, price, description, months_duration, courrier, details, iva, annual_price, status, max_users)
      SELECT id_subscription_type, name, tag, see_lockers, see_services, price, description, months_duration, courrier, details, iva, annual_price, status, max_users FROM [" . $condominio['dbname'] . "].[dbo].[tblSubscriptionType];
      SET IDENTITY_INSERT [tblSubscriptionType] OFF;";
      logger()->debug($sql);
      $con = Database::master_instance();
      $stmt = $con->prepare($sql);
      $stmt->execute();
    } catch (Throwable $th) {
      logger()->error($th);
      throw $th;
    }
  }
}
