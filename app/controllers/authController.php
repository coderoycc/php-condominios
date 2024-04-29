<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use Helpers\Resources\Response;
use App\Models\User;
use App\Providers\AuthProvider;
use Helpers\JWT\JWT;
use Helpers\Resources\Request;

class AuthController {
  public function login_app($data, $files = null) {
    if (!Request::required(['user', 'password', 'pin'], $data))
      Response::error_json(['message' => 'Datos incompletos'], 401);

    $condominioData = Accesos::getCondominio($data['pin']);
    if (!empty($condominioData)) {
      $auth = new AuthProvider(null, $condominioData['dbname']);
      $data = $auth->auth($data['user'], $data['password']);

      $user = $data['user'];
      if ($user->id_user > 0) { // existe el usuario        
        if (!$data['expired']) {
          $validez = time() + 3600 * 24;
          $payload = ['user_id' => $user->id_user, 'user' => $user->user, 'exp' => $validez, 'credential' => $condominioData['dbname']];
          $token = JWT::encode($payload);
          $data['token'] = $token;
          Response::success_json('Login Correcto', $data);
        } else {
          Response::error_json(['message' => 'Su suscripción ha expirado', 'data' => $data], 401);
        }
      } else { // no existe el usuario
        Response::error_json(['message' => 'Credenciales incorrectas'], 401);
      }
    } else {
      Response::error_json(['message' => 'Pin incorrecto'], 401);
    }
  }
}
