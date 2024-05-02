<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Providers\DBWebProvider;
use Helpers\Resources\Response;
use App\Providers\AuthProvider;
use Helpers\JWT\JWT;
use Helpers\Resources\Request;

class AuthController
{
  public function login_app($data, $files = null)
  {
    if (!Request::required(['user', 'password', 'pin'], $data))
      Response::error_json(['message' => 'Datos incompletos'], 401);

    $condominioData = Accesos::getCondominio($data['pin']);
    if (!empty($condominioData)) {
      $auth = new AuthProvider(null, $condominioData['dbname']);
      $data_login = $auth->auth($data['user'], $data['password']);

      $user = $data_login['user'];
      if ($user->id_user > 0) { // existe el usuario        
        if (!$data_login['expired']) {
          $validez = time() + 3600 * 24;
          $payload = ['user_id' => $user->id_user, 'user' => $user->username, 'exp' => $validez, 'credential' => $condominioData['dbname']];
          $token = JWT::encode($payload);
          $data_login['token'] = $token;
          Response::success_json('Login Correcto', $data_login);
        } else {
          Response::error_json(['message' => 'Su suscripción ha expirado', 'data' => $data_login], 401);
        }
      } else { // no existe el usuario
        Response::error_json(['message' => 'Credenciales incorrectas'], 401);
      }
    } else {
      Response::error_json(['message' => 'Pin incorrecto'], 401);
    }
  }
  public function login_web($data, $files = null){
    if (!Request::required(['user', 'password', 'pin'], $data))
      Response::error_json(['message' => 'Datos incompletos'], 401);

    $condominioData = Accesos::getCondominio($data['pin']);
    if (!empty($condominioData)) {
      $auth = new AuthProvider(null, $condominioData['dbname']);
      $res_auth = $auth->auth_web($data['user'], $data['password']);
      if ($res_auth['user']) {
        if ($res_auth['admin']) {
          DBWebProvider::start_session($res_auth['user'], $condominioData);
          Response::success_json('Login Correcto', []);
        } else {
          Response::error_json(['message' => 'Credenciales incorrectas [ADMIN ONLY]'], 401);
        }
      } else {
        Response::error_json(['message' => 'Credenciales incorrectas'], 401);
      }
    } else {
      Response::error_json(['message' => 'Pin incorrecto'], 401);
    }
  }
  public function logout(){
    try {
      DBWebProvider::session_end();
      Response::success_json('Logout Correcto', []);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    Response::error_json(['message' => 'Logout incorrecto'], 401);
  }
}
