<?php

namespace App\Controllers;

use App\Models\Condominius;
use App\Models\Department;
use App\Models\Resident;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class RegisterController {
  public function searchCondominiums($query) {
    $q = $query['q'] ?? '';
    $condominiums = Condominius::search($q);
    Response::success_json("Datos Condominios con $q", $condominiums, 200);
  }
  public function searchDepartments($query) {
    if (!Request::required(['bname'], $query))
      Response::error_json(['message' => 'Campos requeridos [bname]'], 400);
    $q = $query['q'] ?? '';
    $departments = Department::search($q, $query['bname']);
    Response::success_json('Datos departamento ', $departments, 200);
  }


  public function resident($data, $files = null) {
    $required = ['name', 'gender', 'cellphone', 'password', 'depa_id', 'pin'];
    if (!Request::required($required, $data))
      Response::error_json(['message' => 'Campos requeridos [name, gender, cellphone, password, depa_id, pin]'], 400);
    try {
      $resident = new Resident();
      $resident->first_name = $data['name'];
      $resident->gender = $data['gender'];
      $resident->user = $data['cellphone'];
      $resident->cellphone = $data['cellphone'];
      $hash = hash('sha256', $data['password']);
      $resident->password = $hash;
      $resident->status = 1;
      $resident->device_id = $data['device_id'] ?? '00';
      $resident->department_id = $data['depa_id'];
      if ($resident->save() > 0) {
        $login = new AuthController();
        $login->login_app(['user' => $resident->user, 'password' => $data['password'], 'pin' => $data['pin']]);
        // Response::success_json(['message' => 'Registro exitoso'], 200);
      } else {
        Response::error_json(['message' => 'Registro fallido'], 500);
      }
    } catch (\Throwable $th) {
      Response::error_json(['message' => $th->getMessage()], 500);
    }
  }
}
