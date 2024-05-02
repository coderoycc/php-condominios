<?php
namespace App\Models;
class Locker{
  private $con;
  public int $id_locker;
  public int $locker_number;
  public int $locker_status;
  public string $description;
  public function __construct($con = null, $id_locker = null){
    $this->objectNull();
    if($con){
      $this->con = $con;
      if($id_locker){
        $sql = "SELECT * FROM tblLockers WHERE id_locker = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$id_locker]);
        if($res){
          $row = $stmt->fetch();
          if($row)
            $this->load($row);
        }
      }
    }
  }
  public function objectNull(){
    $this->id_locker = 0;
    $this->locker_number = 0;
    $this->locker_status = 0;
    $this->description = "";
  }
  public function load($row){
    $this->id_locker = $row['id_locker'];
    $this->locker_number = $row['locker_number'];
    $this->locker_status = $row['locker_status'] ?? 0;
    $this->description = $row['description'];
  }
  public function save(){
    if($this->con){
      $res = 0;
      if($this->id_locker == 0){//insert
        $sql = "INSERT INTO tblLockers(locker_number, description) VALUES(?,?);";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->description]);
        if($res){
          $this->id_locker = $this->con->lastInsertId();
          $res = $this->id_locker;
        }
      }else{// update
        $sql = "UPDATE tblLockers SET locker_number = ?, description = ? WHERE id_locker = ?;";
        $stmt = $this->con->prepare($sql);
        $res = $stmt->execute([$this->locker_number, $this->description, $this->id_locker]);
        if($res)
          $res = $this->id_locker;
      }
      return $res;
    } 
    return -1;
  }
  public function delete(){
    if($this->con){
      $sql = "DELETE FROM tblLockers WHERE id_locker = ?;";
      $stmt = $this->con->prepare($sql);
      $res = $stmt->execute([$this->id_locker]);
      return $res;
    }
    return false;
  }
  public static function getAll($con, $params){
    try {
      $order_name = $params['order_name'] ?? 'id_locker';
      $order_type = $params['order_type'] ?? 'DESC';
      $sql = "SELECT * FROM tblLockers ORDER BY $order_name $order_type;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute();
      if($res){
        $rows = $stmt->fetchAll();
        if($rows)
          return $rows;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function number_exist($con, $locker_number){
    try {
      $sql = "SELECT * FROM tblLockers WHERE locker_number = ?;";
      $stmt = $con->prepare($sql);
      $res = $stmt->execute([$locker_number]);
      if($res){
        $row = $stmt->fetchAll();
        if(count($row) > 0)
          return true;
        return false;
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return true; // para que no cree el locker
  }
}