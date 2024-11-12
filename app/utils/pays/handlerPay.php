<?php

namespace App\Utils;

use App\Models\Payment;

use const App\Config\EXPIRATION_QR;

/**
 * Clase que maneja las credenciales de pago de la aplicacion todo se ejecuta con CURL, este maneja una instancia
 */
class HandlerPays {
  private $instance = null;
  public function __construct($method = 'POST') {
    $instance = curl_init();
    curl_setopt($instance, CURLOPT_RETURNTRANSFER, true);
  }
  /**
   * 
   * @param Payment $payment
   * @param array $condominio
   * @param string $account
   * @param boolean $singleuse
   * @return HandlerPays
   */
  public function load($payment, $condominio, $account, $singleuse = true) {
    // obtener variables de acuerdo a la cuenta
    $body = [
      'currency' => $payment->currency,
      'amount' => $payment->amount,
      'gloss' => $payment->gloss,
      'serviceCode' => $payment->serviceCode,
      'businessCode' => $payment->bussinessCode,
      'publicToken' => '',
      'sigleUse' => $singleuse,
      'enableBank' => 'ALL',
      'city' => $condominio['city'],
      "branchOffice" => 'Condominio ' . $condominio['name'],
      "teller" => "Caja 1",
      'phoneNumber' => '',
      'expiration' => EXPIRATION_QR,
      'collectors' => []
    ];
    $body = json_encode($body);
    curl_setopt($this->instance, CURLOPT_POSTFIELDS, $body);
    return $this;
  }
  public function pay() {
  }
}
