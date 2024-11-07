<?php

namespace App\Models;

use App\Utils\Queries\QueryBuilder;
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
  /**
   * Guarda un servicio
   * @return int
   */
  public function save() {
    return $this->insert($this->con, 'tblServices', 'id_service');
  }
  public function update($con = null, $ant, $table = 'tblServices', $id = 'id_service') {
    return parent::update($this->con, $ant, $table, 'id_service');
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
        $sql_verify = "SELECT * FROM tblServiceDetailPerMonth WHERE service_id = ?";
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

  public static function subscriptions_enable_to_services() {
    try {
      $query = new QueryBuilder();
      $res = $query->select('tblSubscriptions', 'a')
        ->innerJoin('tblSubscriptionType', 'b', 'a.type_id = b.id_subscription_type AND b.see_services = 1')
        ->innerJoin('tblDepartments', 'c', 'c.id_department = a.department_id')
        ->where("a.expires_in > getdate() AND a.status = 'VALIDO'")
        ->orderBy('a.expires_in DESC')
        ->get('a.*, b.name as tipo_sub, c.*');

      return $res;
    } catch (\Throwable $th) {
      throw $th;
    }
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
  public static function sum_by_subscription($con, $sub_id, $year) {
    try {
      $sql = "
      WITH payments AS (
        SELECT * FROM tblPaymentsServices WHERE [year] = $year AND subscription_id = $sub_id
      )
      SELECT a.*, b.id_payment_service, b.payment_id, b.subscription_id, b.status FROM (
        SELECT [month], sum(amount) as totalmes FROM tblServiceDetailPerMonth WHERE [year] = $year AND service_id IN (
          SELECT id_service FROM tblServices WHERE subscription_id = $sub_id
        ) GROUP BY [month]
      ) a
      LEFT JOIN payments b
      ON a.[month] = b.[month]
      ORDER BY a.[month] DESC;";
      // var_dump($sql);
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
   * @param int $sub_id
   * @return mixed
   */
  public static function detail_for_month($con, $month, $year, $sub_id) {
    try {
      $sql = "SELECT * FROM tblServices a INNER JOIN tblServiceDetailPerMonth b 
            ON a.id_service = b.service_id
            WHERE b.month = $month AND b.year = $year AND a.subscription_id = $sub_id;";
      // var_dump($sql);
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
  static function exist_code($con, $code, $subscription_id) {
    try {
      $sql = "SELECT * FROM tblServices WHERE code = '$code' AND subscription_id = $subscription_id;";
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
      // monto nulo unicamente para conocer los servicios
      $sql = "SELECT * , NULL as amount FROM tblServices WHERE subscription_id = $id_sub;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  /**
   * Listado de servicios por year y filtrados por el estado del pago de un anio
   * @param string $status 'QR PAGADO', 'PAGADO', 
   * @param int $year
   * @return array
   */
  public static function list_filters_all($status = 'SIN PAGO', $year = null) {
    try {
      $query = new QueryBuilder();
      $year = $year ?? date('Y');
      $statuswhere = $status == 'QR PAGADO' || $status == 'PAGADO' ? "WHERE b.status = '$status'" : "";
      $statuswhere = $status == 'SIN PAGO' ? "WHERE b.status IS NULL" : $statuswhere;
      $sql = "
      WITH payments AS (
        SELECT * 
        FROM []tblPaymentsServices 
        WHERE [year] = $year
      )
      SELECT [*]y.dep_number, tmp.* FROM []tblSubscriptions x 
      INNER JOIN []tblDepartments y ON x.department_id = y.id_department
      INNER JOIN (
        SELECT 
          a.subscription_id, 
          a.[month], 
          a.totalmes, 
          b.id_payment_service, 
          b.payment_id, 
          b.status
        FROM (
          SELECT 
              s.subscription_id,
              d.[month], 
              SUM(d.amount) AS totalmes 
          FROM []tblServiceDetailPerMonth d
          JOIN []tblServices s ON d.service_id = s.id_service
          WHERE d.[year] = $year
          GROUP BY s.subscription_id, d.[month]
        ) a
        LEFT JOIN payments b ON a.subscription_id = b.subscription_id AND a.[month] = b.[month]
        $statuswhere
      ) tmp 
      ON tmp.subscription_id = x.id_subscription
      ";
      $res = $query->get_custom($sql);
      return $res;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
