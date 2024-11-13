<?php

namespace App\Services\Interfaces;

use App\Models\Payment;
use App\Models\Subscriptiontype;
use PDO;

interface IPay {
  /**
   * Crea una transaccion para suscripcion dependiento al tipo de suscripcion
   * @param PDO $con conexion 
   * @param array $data Datos de la suscripcion elegida 'resident', 'type' (tipo de suscripcion), 'nit'  
   * @param boolean $annual verificacion si la suscripcion es anual o no
   * @return array retorna un array con el dato del pago y la respuesta del qr
   */
  public function subscription($con, $data, $annual, $condominio): array;
  /**
   * 
   * @param PDO $con
   * @param int $qr_id
   * @param  $annual
   * @return array
   */
  public function get_qr_byid($con, $qr_id, $condominio): array;
  // public function confirm($con, $idPayment);
  // public function add_sub($con, $id_subscription, $id_payment);
}
