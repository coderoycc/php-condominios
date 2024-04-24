<?php

namespace App\Models;

require_once __DIR__ . '/subscriptiontype.php';

use App\Config\Database;
use App\Models\Subscriptiontype;

class Subscription {
  public int $id_subscription;
  public int $type_id;
  public string $paid_in;
  public int $paid_by;
  public string $paid_by_name;
  public int $period;
  public string $nit;
  public int $department_id;

  public Subscriptiontype $type;
  public Department $department;

  public function __construct($id_subscription = null) {
    if ($id_subscription) {
      $con = Database::getInstace();
    } else {
    }
  }

  public function load($row) {
    $this->id_subscription = $row['id_subscription'];
    $this->type_id = $row['type_id'];
    $this->paid_in = $row['paid_in'];
    $this->paid_by = $row['paid_by'];
    $this->paid_by_name = $row['paid_by_name'];
    $this->period = $row['period'];
    $this->nit = $row['nit'];
    $this->department_id = $row['department_id'];
  }

  public function objectNull() {
    $this->id_subscription = 0;
    $this->type_id = 0;
    $this->paid_in = "";
    $this->paid_by = 0;
    $this->paid_by_name = "";
    $this->period = 0;
    $this->nit = "";
    $this->department_id = 0;
  }
  public function type() {
  }

  public static function getTypes($pin) {
    return Subscriptiontype::getTypes($pin);
  }
}
