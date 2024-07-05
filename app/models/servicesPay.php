<?php

namespace App\Models;

/**
 * Objeto que se genera a partir de la tabla tblPaymentServices 
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
