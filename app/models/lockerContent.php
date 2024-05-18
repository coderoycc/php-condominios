<?php

namespace App\Models;

class LockerContent {
  private $con;
  public int $id_content;
  public string $content;
  public int $locker_id;
  public int $user_id_target;
  public string $received_at;
  public function __construct($con = null, $id_content = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id_content == null)
        return;
      $sql = "SELECT * FROM tblLockerContent WHERE id_content = ?";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$id_content]);
      $row = $stmt->fetch();
      if ($row)
        $this->load($row);
    }
  }
  public function objectNull() {
    $this->id_content = 0;
    $this->content = '';
    $this->locker_id = 0;
    $this->user_id_target = 0;
    $this->received_at = '';
  }
  public function load($row) {
    $this->id_content = $row['id_content'];
    $this->content = $row['content'];
    $this->locker_id = $row['locker_id'];
    $this->user_id_target = $row['user_id_target'];
    $this->received_at = $row['received_at'];
  }
  public function save() {
    if ($this->con) {
      $sql = "INSERT INTO tblLockerContent (content, locker_id, user_id_target) VALUES (?, ?, ?)";
      $stmt = $this->con->prepare($sql);
      $stmt->execute([$this->content, $this->locker_id, $this->user_id_target]);
      $this->id_content = $this->con->lastInsertId();
      $this->received_at = date('Y-m-d H:i:s');
      return $this->id_content;
    }
    return -1;
  }
}
