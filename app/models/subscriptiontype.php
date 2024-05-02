<?php

namespace App\Models;

use App\Config\Database;

class Subscriptiontype {
  private $con;
  public int $id;
  public string $name;
  public string $tag;
  public int $price;
  public int $see_lockers;
  public int $see_services;
  public string $description;
  public int $months_duration;

  public function __construct($db = null, $id = 0) {
    $this->objectNull();
    if ($db) {
      $this->con = $db;
      if ($id) {
        $sql = "SELECT * FROM tblSubscriptionType WHERE id_subscription_type = $id;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
          $this->id = $row['id_subscription_type'];
          $this->name = $row['name'];
          $this->tag = $row['tag'];
          $this->price = $row['price'];
          $this->see_lockers = $row['see_lockers'];
          $this->see_services = $row['see_services'];
          $this->description = $row['description'];
          $this->months_duration = $row['months_duration'] ?? 0;
        }
      }
    }
  }
  public function objectNull() {
    $this->id = 0;
    $this->name = '';
    $this->tag = '';
    $this->price = 0.0;
    $this->see_lockers = 0;
    $this->see_services = 0;
    $this->description = '';
    $this->months_duration = 0;
  }
  public static function getTypes($pin) {
    $con = Database::getInstanceByPin($pin);
    $stmt = $con->prepare("SELECT * FROM tblSubscriptionType");
    $stmt->execute();
    $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $rows;
  }
}
