<?php
namespace App\Controllers;
use App\Models\Locker;
use App\Providers\DBWebProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;
class LockerController{
  public function list($data){
    $con = DBWebProvider::getSessionDataDB();
    $lockers = Locker::getAll($con, $data);
    ob_start();
    include(__DIR__.'/../views/locker/list.php');
    $html = ob_get_clean();
    Response::html($html);
  }
  public function get($data){

  }
  public function create($data, $file = null){
    if(DBWebProvider::session_exists()){
      $con = DBWebProvider::getSessionDataDB();
      if($con){
        if(!Locker::number_exist($con, $data['nro'])){
          $locker = new Locker($con);
          $locker->locker_status = $data['status'] ?? 1;
          $locker->locker_number = $data['nro'];
          $locker->description = $data['detail'];
          if($locker->save() > 0){
            Response::success_json('Casillero creado correctamente',['locker' => $locker]);
          }else{
            Response::error_json(['message' => '[Locker ctrl] Error al crear casillero']);
          }
        }else Response::error_json(['message' => 'Número de casillero ya existente']);
      }else
        Response::error_json(['message' => '[Locker ctrl] Error en conexión de instancia']);
    }else{
      Response::error_json(['message' => '[Locker ctrl] Sesión no iniciada']);
    }
  }
  public function delete($data){
    if(!Request::required(['id'], $data))
      Response::error_json(['message' => '[Locker ctrl] Datos incompletos']);
    $con = DBWebProvider::getSessionDataDB();
    $locker = new Locker($con, $data['id']);
    if($locker->id_locker){
      if($locker->delete())
        Response::success_json('Casillero eliminado correctamente', []);
      else
        Response::error_json(['message' => 'Error al eliminar casillero']);
    }else
      Response::error_json(['message' => 'Casillero no existente']);
  }
}