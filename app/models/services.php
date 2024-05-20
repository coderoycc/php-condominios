<?php

namespace App\Models;

class Services {
  private $con;
  public int $id_service;
  public int $user_id;
  public string $service_name;
  public int $service_name_id; // db_master
  public int $department_id;
  public string $code;
  public object $departmemt;
  
  public function __construct($con = null, $id_service = null){
    $this->objectNull();
    if($con){
      $this->con = $con;
      if($id_service){
        $sql = "SELECT * FROM tblServices WHERE id_service = ?";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([$id_service])) {
          $row = $stmt->fetch();
          if ($row)
            $this->load($row);
        }
      }
    }
  }
  public function objectNull(){
    $this->id_service = 0;
    $this->user_id = 0;
    $this->service_name = "";
    $this->service_name_id = 0;
    $this->department_id = 0;
    $this->code = "";
  }
  public function load($row){
    $this->id_service = $row['id_service'];
    $this->user_id = $row['user_id'];
    $this->service_name = $row['service_name'];
    $this->service_name_id = $row['service_name_id'] ?? 0;
    $this->department_id = $row['department_id'];
    $this->code = $row['code'];
  }
  public function department(){}
}
