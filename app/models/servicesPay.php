<?php

namespace App\Models;

use PDO;

use function App\Providers\logger;

/**
 * Objeto que se genera a partir de la tabla tblPaymentServices 
 */
class ServicesPay extends BaseModel {
  private $con;

  public int $id_payment_service;
  public string $month; // month
  public string $year;
  public int $subscription_id;
  public int $payment_id;
  public string $status; // PENDIENTE (al momento de generar qr o iniciar pago), QR PAGADO (pagado por el cliente) y PAGADO (por el administrador)
  public function __construct($con = null) {
    $this->objectNull();
    if ($con != null) {
      $this->con = $con;
    }
  }
  public function find($id = null, $month = null, $year = null, $subscription_id = null) {
    if ($id != null && $id != 0) {
      $sql = "SELECT * FROM tblPaymentsServices WHERE id_payment_service = ?";
      $params = [$id];
    } else if ($month != null && $year != null && $subscription_id != null) {
      $sql = "SELECT * FROM tblPaymentsServices WHERE month = ? AND year = ? AND subscription_id = ?";
      $params = [$month, $year, $subscription_id];
    } else {
      return false;
    }
    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      // logger()->debug(json_encode($row));
      $this->load($row);
      return true;
    }
    return false;
  }
  public function update_status() {
    if ($this->con) {
      $sql = "UPDATE tblPaymentsServices SET status = ? WHERE id_payment_service = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->status, $this->id_payment_service]);
      return true;
    }
    return false;
  }
}
