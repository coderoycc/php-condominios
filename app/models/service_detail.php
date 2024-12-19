<?php

namespace App\Models;

use PDO;
use Throwable;

use function App\Providers\logger;
use function App\Utils\directorio_publico_condominio;
use function App\Utils\directory;

/**
 * Esquematiza y trabaja con la tabla tblServiceDetailPerMonth 
 */
class ServiceDetail {
  private $con = null;
  public int $id_service_detail;
  public int $service_id;
  public float $amount;
  public int $month;
  public int $year;
  public string $filename; // nombre del archivo comprobante
  public function __construct($con = null, $id_service_detail = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_service_detail) {
        $sql = "SELECT * FROM tblServiceDetailPerMonth WHERE id_service_detail = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id_service_detail]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_service_detail = 0;
    $this->service_id = 0;
    $this->amount = 0;
    $this->month = 0;
    $this->year = 0;
    $this->filename = "";
  }
  public function load($row) {
    $this->id_service_detail = $row['id_service_detail'];
    $this->service_id = $row['service_id'];
    $this->amount = $row['amount'] ?? 0;
    $this->month = $row['month'] ?? 0;
    $this->year = $row['year'] ?? 0;
    $this->filename = $row['filename'] ?? "";
  }
  public function insert(): int {
    if ($this->con == null)
      return -1;
    try {
      $sql = "INSERT INTO tblServiceDetailPerMonth(service_id,amount,[month],[year]) VALUES(?,?,?,?);";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->service_id, $this->amount, $this->month, $this->year]);
      $this->id_service_detail = $this->con->lastInsertId();
      return $this->id_service_detail;
    } catch (Throwable $th) {
      logger()->error($th);
    }
    return -1;
  }
  public function update(): int {
    if ($this->con == null)
      return -1;
    try {
      $sql = "UPDATE tblServiceDetailPerMonth SET service_id = ?, amount = ? WHERE id_service_detail = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->service_id, $this->amount, $this->id_service_detail]);
      return $this->id_service_detail;
    } catch (Throwable $th) {
      var_dump($th);
    }
    return -1;
  }
  public function add_voucher_file($pin, $file) {
    if (!isset($file['tmp_name']) || $file['tmp_name'] == "")
      return true;
    if ($this->con) {
      if ($this->filename != "") {
        $this->del_voucher_file($pin);
      }
      $url = directorio_publico_condominio($pin, 'vouchers');
      $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
      $filename = $this->id_service_detail . '_' . date('ymd_Hi') . '.' . $extension;
      $filepath = $url . '\\' . $filename;
      if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $sql = "UPDATE tblServiceDetailPerMonth SET filename = ? WHERE id_service_detail = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$filename, $this->id_service_detail]);
        return true;
      }
    }
    return false;
  }

  public function del_voucher_file($pin) {
    try {
      $url = directorio_publico_condominio($pin, 'vouchers');
      $url .= '\\' . $this->filename;
      if (file_exists($url)) {
        unlink($url);
      }
      $sql = "UPDATE tblServiceDetailPerMonth SET filename = '' WHERE id_service_detail = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->id_service_detail]);
      return true;
    } catch (Throwable $th) {
      logger()->error($th);
    }
    return false;
  }
  public static function get_list() {
  }
  /**
   * Devuelve un valor booleano para verificar si se registro el pago de un mes y año de un departamento
   * @param PDO $con
   * @param int $month
   * @param int $year
   * @param int $sub_id
   * @return bool
   */
  public static function verify_exist($con, $month, $year, $sub_id) {
    try {
      $sql = "SELECT * FROM tblServices a 
              INNER JOIN tblServiceDetailPerMonth b
              ON a.id_service = b.service_id AND a.subscription_id = $sub_id
              WHERE b.[month] = $month AND b.[year] = $year;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($row) {
        return true;
      }
    } catch (Throwable $th) {
      logger()->error($th);
    }
    return false;
  }
  /**
   * Devuelve una lista de los pagos de un mes
   * @param mixed $con
   * @param mixed $month
   * @param mixed $year
   * @param mixed $depa_id
   * @return array
   */
  public static function list_bysub_amounts($con, $year, $month, $sub_id) {
    // otra forma
    // WITH servicepay AS (
    //   SELECT [month], [year], subscription_id, status FROM tblPaymentsServices WHERE subscription_id = 17 AND [year] = 2024
    // ) 
    // SELECT a.*, b.*, c.status, c.payment_id FROM tblServiceDetailPerMonth a 
    // INNER JOIN tblServices b ON a.service_id = b.id_service AND b.subscription_id = 17
    // LEFT JOIN servicepay c ON a.[month] = c.[month] AND a.[year] = c.[year]
    // WHERE a.[year] = 2024 AND a.[month] = 8;
    try {
      $sql = "SELECT a.*, b.*, c.payment_id, c.status
        FROM tblServiceDetailPerMonth a
        INNER JOIN tblServices b 
        ON a.service_id = b.id_service AND b.subscription_id = $sub_id
        LEFT JOIN tblPaymentsServices c ON a.month = c.month AND a.year = c.year AND c.subscription_id = $sub_id
        WHERE a.year = $year AND a.month = $month;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $row;
    } catch (Throwable $th) {
      logger()->error($th);
    }
    return [];
  }
}
