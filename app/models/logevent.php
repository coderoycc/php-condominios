<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use Throwable;

class Logevent {
  public int $id;
  public string $event;
  public string $pin;
  public int $seen;
  public string $created_at;
  public string $target;
  public string $type;

  public function __construct($id = null) {
    try {
      $con = Database::master_instance();
      $this->null_object();
      if ($id) {
        $sql = "SELECT * FROM tblLogEvents WHERE id = $id";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          $this->load($row);
        }
      }
    } catch (Throwable $th) {
      throw $th;
    }
  }
  public function load($row) {
    $this->id = $row['id'];
    $this->event = $row['event'];
    $this->pin = $row['pin'];
    $this->seen = $row['seen'];
    $this->created_at = $row['created_at'];
    $this->target = $row['target'];
    $this->type = $row['type'];
  }
  public function null_object() {
    $this->id = 0;
    $this->event = '';
    $this->pin = '';
    $this->seen = 0;
    $this->created_at = '';
    $this->target = '';
    $this->type = '';
  }
  public function save() {
    try {
      $con = Database::master_instance();
      $sql = "INSERT INTO tblLogEvents (event, pin, target, type) VALUES (:event, :pin, :target, :type)";
      $stmt = $con->prepare($sql);
      $stmt->execute(['event' => $this->event, 'pin' => $this->pin, 'target' => $this->target, 'type' => $this->type]);
      $this->id = $con->lastInsertId();
      return $this->id;
    } catch (Throwable $th) {
      throw $th;
    }
  }
  public static function all($top = 15, $filters = []) {
    try {
      $where = isset($filters['seen']) ? 'WHERE seen = 1' : '';
      // $where .= isset($filters['pin']) ? ' AND type = ' . $filters['type'] : '';
      $con = Database::master_instance();
      $sql = "SELECT * FROM tblLogEvents $where ORDER BY id DESC LIMIT $top";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $events = [];
      foreach ($rows as $row) {
        $event = new Logevent();
        $event->load($row);
        $events[] = $event;
      }
      return $events;
    } catch (Throwable $th) {
      throw $th;
    }
  }
}
