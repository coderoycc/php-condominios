<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Condominius;
use App\Models\Department;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\User;
use App\Providers\AuthProvider;
use Helpers\Resources\HandleDates;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class RegisterController {
  public function with_code($body, $files = null) {
    if (!Request::required(['pin', 'cellphone', 'password', 'code', 'gender', 'name'], $body))
      Response::error_json(['message' => 'Campos requeridos [pin, cellphone, password, code]'], 400);
    $con = Database::getInstanceByPin($body['pin']);
    $subscription = new Subscription($con, null, $body['code']);
    if ($subscription->id_subscription) {
      if (!HandleDates::expired($subscription->expires_in)) {
        $subscription->type();
        if ($subscription->users() < $subscription->type->max_users) {
          $resident = new Resident($con);
          $resident->cellphone = $body['cellphone'];
          $resident->username = $body['cellphone'];
          $resident->first_name = $body['name'];
          $resident->gender = $body['gender'];
          $resident->role = 'resident';
          $resident->status = 1;
          $resident->password = hash('sha256', $body['password']);
          $resident->department_id = $subscription->department_id;
          $resident->save();
          Subscription::addUserSubscription($con, $resident->id_user, $subscription->id_subscription);
          Response::success_json('Registro exitoso', $resident, 200);
        } else
          Response::error_json(['message' => 'Se ha alcanzado el número máximo de usuarios'], 400);
      } else
        Response::error_json(['message' => 'La suscripción ya expiró'], 404);
    } else
      Response::error_json(['message' => 'Código no válido'], 404);
  }
  public function searchCondominiums($query) {
    $q = $query['q'] ?? '';
    $condominiums = Condominius::search($q);
    Response::success_json("Datos Condominios con $q", $condominiums, 200);
  }
  public function searchDepartments($query) {
    if (!Request::required(['bname'], $query))
      Response::error_json(['message' => 'Campos requeridos [bname]'], 400);
    $q = $query['q'] ?? '';
    $con = Database::getInstanceByPin($query['bname']);
    $departments = Department::search($con, $q);
    Response::success_json('Datos departamento ', $departments, 200);
  }
  public function usernameExist($data) {
    if (!Request::required(['user', 'pin'], $data))
      Response::error_json(['message' => 'Campos requeridos [user, pin]'], 400);

    if (User::usernameExist($data['user'], $data['pin'])) {
      Response::error_json(['message' => '¡Usuario existente! Inicie sesión con su usuario.', 'user' => $data['user']], 400);
    } else {
      Response::success_json('[OK] El usuario no está registrado', [], 200);
    }
  }

  public function resident($data, $files = null) {
    $required = ['name', 'gender', 'cellphone', 'password', 'depa_id', 'pin', 'check'];
    if (!Request::required($required, $data))
      Response::error_json(['message' => 'Campos requeridos [name, gender, cellphone, password, depa_id, pin, check]'], 400);
    try {
      $database = Accesos::getCondominio($data['pin']);
      if (!isset($database['dbname']))
        Response::error_json(['message' => 'Pin no válido'], 400);

      if (User::usernameExist($data['cellphone'], $data['pin']))
        Response::error_json(['message' => '¡Usuario existente! Inicie sesión con su usuario.', 'user' => $data['cellphone']], 400);

      $con = Database::getInstanceX($database['dbname']);

      if ($data['check']) { // existe un codigo, asociar a suscripcion?  
        $code = $data['code'] ?? 'ABCDEF';
        $code_subs = Subscription::getSubscriptionByCode($con, $code, $data['depa_id']);
        if (!$code_subs['valid']) {
          Response::error_json(['message' => 'Código de suscripción no válido']);
        } else if ($code_subs['limit_reached']) {
          Response::error_json(['message' => 'Limite alcanzado para el código de suscripción']);
        } else if (!$code_subs['depa_ok']) {
          Response::error_json(['message' => "Este código $code, no pertenece a su departamento"]);
        }
      }

      $resident = new Resident($con, null);
      $resident->first_name = $data['name'];
      $resident->gender = $data['gender'];
      $resident->username = $data['cellphone'];
      $resident->cellphone = $data['cellphone'];
      $hash = hash('sha256', $data['password']);
      $resident->password = $hash;
      $resident->status = 1;
      $resident->device_id = $data['device_id'] ?? '00-00-000';
      $resident->department_id = $data['depa_id'];
      $resp = $resident->save();
      if ($resp > 0) {
        if ($data['check']) { // suscripción usuario con
          $r = Subscription::addUserSubscription($con, $resp, $code_subs['subs_id']);
          if (!$r)
            Response::error_json(['message' => 'Error al suscribir usuario']);
        }
        unset($resident->password);
        Response::success_json('Registro exitoso', ['user' => $resident], 200);
      } else {
        Response::error_json(['message' => 'Registro fallido'], 500);
      }
    } catch (\Throwable $th) {
      Response::error_json(['message' => $th->getMessage()], 500);
    }
  }
}
