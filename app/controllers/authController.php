<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Models\Subscription;
use Helpers\Resources\Response;
use App\Models\User;
use Helpers\JWT\JWT;

class AuthController {
  public function login_app($data, $files = null) {
    if (isset($data['user']) && isset($data['password']) && isset($data['pin'])) {
      $condominioData = Accesos::getCondominio($data['pin']);
      if (!empty($condominioData)) {
        $user = User::exist($data['user'], $data['password'], $condominioData['dbname']);
        if ($user->id_user) {
          // verificar suscripcion y verificar fecha de expiracion
          // $suscrip = new Subscription();
          $validez = time() + 3600 * 24;
          $payload = ['user_id' => $user->id_user, 'user' => $user->user, 'exp' => $validez, 'credential' => $condominioData['dbname']];
          $token = JWT::encode($payload);
          Response::success_json(['data' => ['token' => $token, 'subscription' => null], 'message' => 'Login Correcto'], 200);
        } else {
          Response::error_json(['message' => 'Credenciales incorrectas', 'user' => $user], 401);
        }
      } else {
        Response::error_json(['message' => 'Pin incorrecto'], 401);
      }
    } else {
      Response::error_json(['message' => 'Datos incompletos'], 401);
    }
  }
}
