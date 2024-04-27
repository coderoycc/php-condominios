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

  public function __construct($db = null, $id) {
    if ($db) {
      $this->con = $db;
      if ($id) {
        $sql = "SELECT * FROM tblSubscriptionType WHERE id_subscription_type = $id;";
        $stmt = $this->con->prepare($sql);
        $row = $stmt->fetch();
        if ($row) {
          $this->id = $row['id_subscription_type'];
          $this->name = $row['name'];
          $this->tag = $row['tag'];
          $this->price = $row['price'];
          $this->see_lockers = $row['see_lockers'];
          $this->see_services = $row['see_services'];
        }
      }
    }
  }
  public static function getTypes($pin) {
    $con = Database::getInstanceByPin($pin);
    $stmt = $con->prepare("SELECT * FROM tblSubscriptionType");
    $stmt->execute();
    $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $rows;
  }
}
