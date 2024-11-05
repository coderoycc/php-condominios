<?php

namespace App\Models;

use App\Config\Database;
use App\Utils\Queries\QueryBuilder;
use PDO;

class Subscriptiontype {
  private $con;
  public int $id;
  public string $name;
  public string $tag;
  public float $price;
  public float $annual_price;
  public int $see_lockers;
  public int $see_services;
  public int $courrier;
  public string $description;
  public int $months_duration;
  public int $iva;
  public int $status; // 0: inactivo, 1: activo
  public int $max_users; //cantidad maxima de usuarios
  public array $details;

  public function __construct($db = null, $id = 0) {
    $this->objectNull();
    if ($db) {
      $this->con = $db;
      if ($id) {
        $sql = "SELECT * FROM tblSubscriptionType WHERE id_subscription_type = $id;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row)
          $this->load($row);
      }
    }
  }
  public function load($row) {
    $this->id = $row['id_subscription_type'];
    $this->name = $row['name'] ?? '';
    $this->tag = $row['tag'] ?? '';
    $this->price = $row['price'] ?? 0;
    $this->annual_price = $row['annual_price'] ?? 0.0;
    $this->iva = $row['iva'] ?? 0;
    $this->see_lockers = $row['see_lockers'] ?? 0;
    $this->see_services = $row['see_services'] ?? 0;
    $this->courrier = $row['courrier'] ?? 0;
    $this->description = $row['description'] ?? '';
    $this->months_duration = $row['months_duration'] ?? 0;
    $this->max_users = $row['max_users'] ?? 0;
    $this->status = $row['status'] ?? 0;
    $this->details = $row['details'] != null ? json_decode($row['details'], true) : [];
  }
  public function objectNull() {
    $this->id = 0;
    $this->name = '';
    $this->tag = '';
    $this->price = 0.0;
    $this->annual_price = 0.0;
    $this->iva = 0;
    $this->see_lockers = 0;
    $this->see_services = 0;
    $this->courrier = 0;
    $this->description = '';
    $this->months_duration = 0;
    $this->details = [];
    $this->max_users = 0;
    $this->status = 0;
  }
  public function delete() {
    if ($this->con) {
      if ($this->id != 0) {
        $sql = "DELETE FROM tblSubscriptionType WHERE id_subscription_type = $this->id";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute();
        if ($res) {
          $this->objectNull();
          return true;
        }
      }
    }
    return false;
  }
  public function save() {
    if ($this->con) {
      if ($this->id == 0) {
        $sql = "INSERT INTO tblSubscriptionType (name, tag, price, see_lockers, see_services, description, months_duration, courrier) VALUES (:name, :tag, :price, :see_lockers, :see_services, :description, :months_duration, :courrier)";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute(['name' => $this->name, 'tag' => $this->tag, 'price' => $this->price, 'see_lockers' => $this->see_lockers, 'see_services' => $this->see_services, 'description' => $this->description, 'months_duration' => $this->months_duration,]);
        if ($res) {
          $this->id = $this->con->lastInsertId();
          return true;
        }
        return false;
      }
    }
    return false;
  }
  public static function getTypes($pin = null, $con = null) {
    try {
      if ($pin) {
        $con = Database::getInstanceByPin($pin);
        $stmt = $con->prepare("SELECT * FROM tblSubscriptionType");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
      } else if ($con) {
        $stmt = $con->prepare("SELECT * FROM tblSubscriptionType");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function dependency_exist($con, $id): bool {
    $sql = "SELECT * FROM tblSubscriptions WHERE type_id = $id";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    if ($rows)
      return true;
    return false;
  }
  /**
   * Recibe una instancia de subscriptiontype y actualiza en todas las bases de datos. 
   * @param Subscriptiontype $type
   * @return bool
   */
  public static function update_all(Subscriptiontype $type) {
    try {
      $details = json_encode($type->details);
      $sql_custom = "UPDATE [**]tblSubscriptionType SET name = '$type->name', tag = '$type->tag', see_lockers = $type->see_lockers, see_services = $type->see_services, price = $type->price, description = '$type->description', months_duration = $type->months_duration, courrier = $type->courrier, details = '$details', annual_price = $type->annual_price, status = $type->status, max_users = $type->max_users WHERE id_subscription_type = $type->id;";

      $query = new QueryBuilder();
      $res = $query->query_custom($sql_custom);
      return $res;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
