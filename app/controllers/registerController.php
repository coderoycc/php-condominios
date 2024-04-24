<?php

namespace App\Controllers;

use App\Models\Condominius;
use App\Models\Department;
use App\Models\Resident;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class RegisterController {
  public function searchCondominiums($query) {
    $condominiums = Condominius::search($query['q'] ?? '');
    Response::success_json(['data' => $condominiums], 200);
  }
  public function searchDepartments($query) {
    $departments = Department::search($query['q'] ?? '', $query['bname']);
    Response::success_json(['data' => $departments], 200);
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
