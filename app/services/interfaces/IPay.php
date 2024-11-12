<?php

namespace App\Services\Interfaces;

use App\Models\Payment;
use App\Models\Subscriptiontype;
use PDO;

interface IPay {
  /**
   * Crea una nueva transaccion QR haciendo peticion al servidor del banco
   * @param PDO $con Conexion perteneciente al conominio
   * @param array $condominio Objeto del condominio para poder definir el collector
   * @param float $amount Monto de la transaccion 
   * @param string $gloss glosa de la transaccion
   * @param string $phoneNumber numero de telefono del usuario
   * @param Payment $payment Detalles del pago 
   * @return mixed Retorna la respuesta del a la API QR del banco
   */
  public function new($con, $condominio, $amount, $gloss, $phoneNumber, $payment): mixed;
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
   * @return mixed
   */
  public function get_qr_byid($con, $qr_id, $condominio): mixed;
  // public function confirm($con, $idPayment);
  // public function add_sub($con, $id_subscription, $id_payment);
}
