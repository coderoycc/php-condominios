<?php

namespace App\Controllers;

use App\Models\Motivo;

class CMotivo {
  public function __construct() {
  }
  public function getByName($data) {
    $motivo = Motivo::getList($data['q']);
    echo json_encode($motivo);
  }
}
