<?php

namespace App\Models;

use PDO;

class Ads {
}
class Advertiser {
  private $con;
  public int $id_advertiser;
  public string $name;
  public string $created_at;
  public string $created_by;
  public function __construct($con = null, $id = null) {
    $this->objectNull();
    if ($con) {
      $this->con = $con;
      if ($id) {
        $sql = "SELECT * FROM tblAdvertiser WHERE id_advertiser = ?;";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
          $this->load($row);
        }
      }
    }
  }
  public function objectNull() {
    $this->id_advertiser = 0;
    $this->name = "";
    $this->created_at = "";
    $this->created_by = "";
  }
  public function load($row) {
    $this->id_advertiser = $row['id_advertiser'];
    $this->name = $row['name'];
    $this->created_at = $row['created_at'];
    $this->created_by = $row['created_by'];
  }
}
