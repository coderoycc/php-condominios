<?php

namespace App\Models;

/**
 * Pago de servicios agrupa todos los pagos de un departamento y los suma
 * Une las tablas 
 */
class ServicesPay {
  private $con;
  public object $service; //Objeto de la clase Services
  public float $amount;
  public string $date; // month 
  public function __construct() {
  }
  public function objectNull() {
  }
  public function load($row) {
  }
}
