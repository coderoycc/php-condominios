<?php

namespace App\Models;

use App\Config\Accesos;
use App\Config\Database;
use PDO;
use Throwable;

use function App\Providers\logger;

class Logevent {
  public int $id;
  public string $event;
  public string $event_detail;
  public string $pin;
  public int $seen;
  public string $created_at;
  public string $target;
  public string $type;

  public function __construct($id = null) {
    try {
      $con = Database::getInstaceCondominios();
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
    $this->event_detail = $row['event_detail'];
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
    $this->event_detail = '';
  }
  public function update_seen() {
    try {
      $sql = "UPDATE tblLogEvents SET seen = ? WHERE id = ?";
      $con = Database::getInstaceCondominios();
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->seen, $this->id]);
    } catch (Throwable $th) {
      logger()->error($th);
    }
  }
  public function save() {
    try {
      $con = Database::getInstaceCondominios();
      $sql = "INSERT INTO tblLogEvents (event, pin, target, type, event_detail) VALUES (:event, :pin, :target, :type, :event_detail)";
      $stmt = $con->prepare($sql);
      $stmt->execute(['event' => $this->event, 'pin' => $this->pin, 'target' => $this->target, 'type' => $this->type, 'event_detail' => $this->event_detail]);
      $this->id = $con->lastInsertId();
      return $this->id;
    } catch (Throwable $th) {
      throw $th;
    }
  }

  public static function all($top = 15, $filters = []) {
    try {
      $where = isset($filters['no_seen']) ? 'WHERE seen = 0' : '';
      $where = isset($filters['seen']) ? 'WHERE seen = 1' : $where;
      $con = Database::getInstaceCondominios();
      $sql = "SELECT TOP $top * FROM tblLogEvents $where ORDER BY id DESC";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $events = [];
      foreach ($rows as $row) {
        $event = new Logevent();
        $event->load($row);
        $condominio = Accesos::getCondominio($event->pin);
        unset($condominio['dbname']);
        unset($condominio['pin']);
        $event->{'condominio'} = $condominio;
        $events[] = $event;
      }
      return $events;
    } catch (Throwable $th) {
      throw $th;
    }
  }
}
