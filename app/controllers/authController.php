<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Models\Master;
use App\Providers\DBWebProvider;
use Helpers\Resources\Response;
use App\Providers\AuthProvider;
use Exception;
use Helpers\JWT\JWT;
use Helpers\Resources\Request;

class AuthController {
  public function login_app($data, $files = null) /*app*/ {
    if (!Request::required(['user', 'password', 'pin', 'device_id'], $data))
      Response::error_json(['message' => 'Datos incompletos'], 401);

    $device_code = $data['device_code'] ?? '';
    $condominioData = Accesos::getCondominio($data['pin']);
    if (!empty($condominioData)) {
      $dbname = $condominioData['dbname'];
      unset($condominioData['dbname']);
      $auth = new AuthProvider(null, $dbname);
      $data_login = $auth->auth($data['user'], $data['password']);
      $data_login['condominio'] = $condominioData;
      $user = $data_login['user'];
      if ($user->id_user > 0) { // existe el usuario
        $res_log = $user->verify_code($device_code);
        if (!$res_log['status']) // verificar si se loguea de otro celular
          Response::error_json(['message' => $res_log['message']], 401);

        $user->device_id = $data['device_id'];
        $user->save();
        if (!$data_login['expired'] && $data_login['status'] == 'VALIDO') { //suscripcion no vencida
          $validez = time() + 3600 * 24; // 1 dia
          $codicationdb = base64_encode(base64_encode($dbname));
          $codificationPIN = base64_encode(base64_encode($data['pin']));
          $sub = $data_login['subscription'];
          $sub_data = base64_encode(base64_encode('S-' . $sub->id_subscription));
          $payload = ['user_id' => $user->id_user, 'user' => $user->username, 'exp' => $validez, 'credential' => $codicationdb, 'pin' => $codificationPIN, 'us_su' => $sub_data];
          $token = JWT::encode($payload);
          $data_login['token'] = $token;
          Response::success_json('Login Correcto', $data_login);
        } else
          Response::error_json(['message' => 'Su suscripción ha expirado', 'data' => $data_login], 401);
      } else  // no existe el usuario
        Response::error_json(['message' => 'Credenciales incorrectas'], 401);
    } else
      Response::error_json(['message' => 'Pin incorrecto'], 401);
  }
  public function login_web($data, $files = null) {
    if (!Request::required(['user', 'password', 'pin'], $data))
      Response::error_json(['message' => 'Datos incompletos'], 401);

    $condominioData = Accesos::getCondominio($data['pin']);
    if (!empty($condominioData)) {
      $auth = new AuthProvider(null, $condominioData['dbname']);
      $res_auth = $auth->auth_web($data['user'], $data['password']);
      if ($res_auth['user']) {
        if ($res_auth['admin']) {
          $condominios = Master::get_condominios("WHERE pin != '" . $data['pin'] . "'");
          DBWebProvider::start_session($res_auth['user'], $condominioData, $condominios);
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
  public function change_credentials($body, $files = null) {
    try {
      if (DBWebProvider::session_exists()) {
        $condominioData = Accesos::getCondominio($body['pin']);
        $user = DBWebProvider::session_get_user();
        $condominios = Master::get_condominios("WHERE pin != '" . $body['pin'] . "'");
        DBWebProvider::start_session($user, $condominioData, $condominios);
        Response::success_json('Login Correcto', []);
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    Response::error_json(['message' => 'Error al cambiar de sesion'], 200);
  }
  public function logout() {
    try {
      DBWebProvider::session_end();
      Response::success_json('Logout Correcto', []);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    Response::error_json(['message' => 'Logout incorrecto'], 401);
  }
}
