<?php

namespace App\Models;

class QR {
  public static function generarQR(Payment $payment) {
    // hacer peticion 

    $data = file_get_contents(__DIR__ . '/dataPay.json');

    return json_decode($data);
  }
}
