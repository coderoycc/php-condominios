<?php

namespace App\Models;

require_once(__DIR__ . '/baseModel.php');

use App\Models\BaseModel;
use PDO;

class Shipping extends BaseModel {
  private $con;
  public int $id;
  public string $name_origin;
  public string $country_origin;
  public string $address_origin;
  public string $postal_code_origin;
  public string $city_origin;
  public string $nif_origin;

  public string $name_destiny;
  public string $country_destiny;
  public string $address_destiny;
  public string $postal_code_destiny;
  public string $city_destiny;
  public string $nif_destiny;

  public float $weight; //peso kilos
  public float $h; // altura cm
  public float $l; // largo cm
  public float $w; // ancho cm
  public float $volume;
  public float $price;
  public string $currency; // tipo moneda
  public string $tracking_id;
  public string $status; // estado del envio PENDIENTE | EN PROCESO | PARA ENVIAR | ENVIADO
  public int $department_id;
  public string $created_at;
  public int $created_by; // id usuario residente
  public int $nat; // 1: nacional, 0: internacional

  public string $phone_origin;
  public string $phone_destiny;
  public string $company; // Empresa de envio
  public int $envelope; // 1: es sobre, 0: no es sobre

  public function __construct($con = null, $id = null) {
    $this->objectNull();
    if ($con != null) {
      $this->con = $con;
      if ($id != null) {
        $sql = "SELECT * FROM tblShipping WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result)
          $this->load($result);
      }
    }
  }
  public function insert($con = null, $table = 'tblShipping', $id = 'id') {
    $r = -1;
    if ($this->con != null) {
      $r = parent::insert($this->con, $table, $id);
    }
    return $r;
  }
  /**
   * Carga todos los objetos dependencias como 'department', 'payment'
   * @return void
   */
  public function load_full_data() {
    if ($this->con != null) {
      try {
        $sql = "SELECT d.*, y.* FROM tblShipping a 
        LEFT JOIN tblDepartments d ON a.department_id = d.id_department 
        LEFT JOIN tblPaymentShipping p ON p.payment_id = a.id 
        LEFT JOIN tblPayments y ON y.idPayment = p.payment_id
        WHERE a.id = ?;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          $department = new Department();
          $department->load($result);
          $this->{'department'} = $department;
          if ($result['idPayment'] != null) {
            $payment = new Payment();
            $payment->load($result);
            $this->{'payment'} = $payment;
          } else {
            $this->{'payment'} = null;
          }
        }
      } catch (\Throwable $th) {
        var_dump($th);
      }
    }
  }
  /**
   * Actualiza datos de segun los cambios realizados con el objeto incial (initial)
   * @param mixed $con
   * @param Shipping $initial
   * @param string $table
   * @param string $id
   * @return int
   */
  public function update($con = null, $initial, $table = 'tblShipping', $id = 'id') {
    $r = -1;
    if ($this->con != null) {
      $r = parent::update($this->con, $initial, $table, $id);
    }
    return $r;
  }
  /**
   * Devuelve todos los envios con filtros adicionales ['department_id', 'status']
   * @param PDO $con
   * @param array $filters
   * @return mixed
   */
  public static function get_all($con, $filters = []) {
    try {
      $department = isset($filters['department_id']) ? "a.department_id = " . $filters['department_id'] : '';
      $status = isset($filters['status']) ? "a.status = '" . $filters['status'] . "'" : '';
      $sql = "SELECT a.*, d.dep_number FROM tblShipping a INNER JOIN tblDepartments d ON a.department_id = d.id_department";
      if ($department != "" && $status != '') {
        $sql .= " WHERE $department AND $status";
      } else if ($department != '' && $status == "") {
        $sql .= " WHERE $department";
      } else if ($department == '' && $status != '') {
        $sql .= " WHERE $status";
      }
      $sql .= " ORDER BY a.id DESC";
      // var_dump($sql);
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
}
