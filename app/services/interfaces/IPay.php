<?php

namespace App\Services\Interfaces;

use App\Models\Payment;
use PDO;

interface IPay {
  /**
   * Crea una nueva transaccion QR haciendo peticion al servidor del banco
   * @param PDO $con Conexion perteneciente al conominio
   * @param array $condominio Objeto del condominio para poder definir el collector
   * @param float $amount Monto de la transaccion 
   * @param string $gloss glosa de la transaccion
   * @param string $phoneNumber numero de telefono del usuario
   * @param string $city ciudad del condominio
   * @return Payment Transaccion creada
   */
  public function new($con, $condominio, $amount, $gloss, $phoneNumber, $city): Payment;
  /**
   * 
   * @param PDO $con
   * @param int $qr_id
   * @param  $annual
   * @return Payment
   */
  public function getqr($con, $qr_id, $condominio): Payment;
  // public function confirm($con, $idPayment);
  // public function add_sub($con, $id_subscription, $id_payment);
}
