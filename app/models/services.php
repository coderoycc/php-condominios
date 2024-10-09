<?php

namespace App\Models;

use PDO;

require_once(__DIR__ . '/service_detail.php');
require_once(__DIR__ . '/baseModel.php');
class Services extends BaseModel {
  private $con;
  public int $id_service;
  public int $user_id;
  public string $service_name;
  public int $service_name_id; // db_master
  public int $subscription_id;
  public string $code;
  public array $pay_services;

  public function __construct($con = null, $id_service = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_service) {
        $sql = "SELECT * FROM tblServices WHERE id_service = ?";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([$id_service])) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($row)
            $this->load($row);
        }
      }
    }
  }
  public function save(): int {
    return parent::insert($this->con, 'tblServices', 'id_service');
  }
  /**
   * Elimina un servicio
   * @param int $id_service
   * @return array ['status' => boolean, 'message' => string]
   */
  public function delete() {
    $resp = ['status' => false, 'message' => ''];
    try {
      if ($this->con) {
        $sql_verify = "SELECT * FROM tblServiceDetail WHERE service_id = ?";
        $stmt_verify = $this->con->prepare($sql_verify);
        $stmt_verify->execute([$this->id_service]);
        $row = $stmt_verify->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) > 0) {
          $resp['message'] = 'No se puede eliminar el servicio porque tiene detalles asociados';
          return $resp;
        }
        $sql = "DELETE FROM tblServices WHERE id_service = ?";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([$this->id_service])) {
          $resp['message'] = 'Servicio eliminado correctamente';
          $resp['status'] = true;
        } else {
          $resp['message'] = 'Error al eliminar el servicio';
        }
      } else {
        $resp['message'] = 'Error DB sin instancia';
      }
    } catch (\Throwable $th) {
      $resp['message'] = $th->getMessage();
    }
    return $resp;
  }
  /**
   * Funcion que devuelve todas las suscripciones que estan habilitadas para ver servicios
   * @param PDO $con Conexion PDO
   * @param array $filters 
   * @return mixed
   */
  public static function subs_all($con, $filters) {
    try {
      $sql = "SELECT a.id_subscription, a.expires_in, a.code, b.name, c.dep_number, c.id_department FROM tblSubscriptions a 
        INNER JOIN tblSubscriptionType b ON a.type_id = b.id_subscription_type AND b.see_services = 1 AND a.expires_in > GETDATE()
        INNER JOIN tblDepartments c ON c.id_department = a.department_id WHERE a.status = 'VALIDO';";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * metodo que devuelve los montos de servicios de un departamento junto a los pagos (QR) si es que se generaron, usando un año pasado por parametro
   * @param PDO $con
   * @param int $depa_id
   * @param int $year
   * @return mixed
   */
  public static function sum_department($con, $depa_id, $year) {
    try {
      $sql = "
      WITH pagos AS (
        SELECT 
        a.department_id,
        a.payment_id,
        a.status,
        MONTH(a.target_date) as mes
        FROM tblPaymentsServices a 
        WHERE YEAR(target_date) = $year 
        AND department_id = $depa_id
      )
      SELECT a.*, b.department_id, b.status, b.payment_id FROM (
        SELECT MONTH(month) as mes, ROUND(sum(amount), 2) as total FROM tblServiceDetail
          WHERE service_id IN (
            SELECT id_service FROM tblServices WHERE department_id = $depa_id
          )
          AND YEAR(month) = $year
          GROUP BY MONTH(month)
      ) as a
      LEFT JOIN pagos b ON a.mes = b.mes
      ORDER BY a.mes DESC";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Detalle de los sevicios por mes y departamento
   * @param PDO $con Conexion PDO
   * @param int $month  
   * @param int $year
   * @param int $depa_id
   * @return mixed
   */
  public static function detail_for_month($con, $month, $year, $depa_id) {
    try {
      $sql = "SELECT * FROM tblServices a
        INNER JOIN tblServiceDetail b ON a.id_service = b.service_id AND a.department_id = $depa_id
        WHERE MONTH(b.month) = $month AND YEAR(b.month) = $year;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Verifica si un codigo ya existe en la base de datos
   * @param PDO $con
   * @param string $code
   * @param int $department_id
   * @return boolean
   */
  static function exist_code($con, $code, $department_id) {
    try {
      $sql = "SELECT * FROM tblServices WHERE code = '$code' AND department_id = $department_id;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows) {
        if (count($rows) > 0)
          return true;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return false;
  }
  public static function list_by_subscription($con, $id_sub) {
    try {
      $sql = "SELECT * FROM tblServices WHERE subscription_id = $id_sub;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
