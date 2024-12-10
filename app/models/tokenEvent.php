<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;
use Ramsey\Uuid\Nonstandard\Uuid;
use Throwable;

use function App\Providers\logger;

class TokenEvent extends BaseModel {
  private $con;
  public int $id;
  public int $user_id;
  public string $token;
  public int $used;
  public string $created_at;
  public string $expires_at;
  public string $event;
  public function __construct($con = null, $id = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id) {
        $sql = "SELECT * FROM tblUserTokenEvents WHERE id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data)
          $this->load($data);
      }
    }
  }
  public function new() {
    try {
      $this->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
      $token = strtoupper(substr(uniqid(), -6));
      $this->token = $token;
      $sql = "INSERT INTO tblUserTokenEvents(user_id, token, expires_at, event) VALUES(?, ?, '$this->expires_at', ?);";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->user_id, $this->token, $this->event]);
      $this->id = $this->con->lastInsertId();
    } catch (Throwable $th) {
      logger()->error($th);
    }
  }
}
