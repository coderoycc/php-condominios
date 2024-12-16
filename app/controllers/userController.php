<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Resident;
use App\Models\User;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

use function App\Providers\logger;

class UserController {
  public function create($data, $files) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $usuario = new User($con);
      $usuario->username = $data['usuario'];
      $usuario->password = hash('sha256', $data['usuario']);
      // print_r($usuario);
      $usuario->role = $data['rol'];
      $usuario->first_name = $data['nombre'];
      $usuario->status = 1;
      $usuario->gender = 'O';
      $res = $usuario->save();
      if ($res) {
        unset($usuario->password);
        Response::success_json('Usuario creado correctamente', ['user' => $usuario]);
      } else {
        Response::error_json(['message' => 'Error al crear el usuario'], 200);
      }
    } else {
      Response::error_json(['message' => 'Error conexion instancia'], 200);
    }
  }

  public function delete($data) /* WEB */ {
    $id = $data['idUsuario'];
    $con = DBWebProvider::getSessionDataDB();
    $usuario = new User($con, $id);
    if ($usuario->id_user == 0) {
      Response::error_json(['message' => 'Usuario no encontrado'], 200);
    } else {
      $res = $usuario->delete();
      if ($res > 0) {
        Response::success_json('El usuario fue eliminado exitosamente', []);
      } else
        Response::error_json(['message' => 'No se elimino el usuario'], 200);
    }
  }
  public function update($data) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con == null)
      Response::error_json(['message' => 'Error conexion instancia'], 200);
    $idUsuario = $data['idUsuario'];
    $user = $data['usuario'];
    $rol = $data['rol'];
    $usuario = new User($con, $idUsuario);
    if ($usuario->id_user == 0) {
      Response::error_json(['message' => 'No existe el usuario | idUsuario incorrecto'], 200);
    } else {
      $usuario->username = $user;
      $usuario->role = $rol;
      $usuario->first_name = $data['nombre'];
      $res = $usuario->save();
      if ($res > 0) {
        Response::success_json('Acutalizado correctamente', [], 200);
      } else {
        Response::error_json(['message' => 'Error al actualizar el usuario'], 200);
      }
    }
  }
  public function resetPass($data)/* WEB */ {
    $id = $data['idUsuario'];
    $con = DBWebProvider::getSessionDataDB();
    $usuario = new User($con, $id);
    if ($usuario->id_user == 0) {
      Response::error_json(['message' => 'Usuario no existente'], 200);
    } else {
      if ($usuario->resetPass()) {
        Response::success_json('Contraseña actualizada', []);
      } else
        Response::error_json(['message' => 'No se realizó el cambio la contraseña'], 200);
    }
  }
  public function get_data_resident($data) {
    if (!Request::required(['pin', 'user_id'], $data))
      Response::error_json(['message' => 'Parametros faltantes']);


    $condominio_data = Accesos::getCondominio($data['pin']);
    if (isset($condominio_data['dbname'])) {
      $con = Database::getInstanceX($condominio_data['dbname']);
      $resident = new Resident($con, $data['user_id']);
      $resident->department();
      unset($resident->password);
      Response::success_json('Usuario residente', ['residente' => $resident, 'condominio' => $condominio_data]);
    } else {
      Response::error_json(['message' => 'Pin incorrecto']);
    }
  }
  public function get_admins($data) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $admins = User::all_users($con);
      Response::success_json('Administradores', $admins);
    } else Response::error_json(['message' => 'Sin conexión a la base de datos'], 200);
  }
  public function search_with_department($query)/*protected*/ {
    if (!Request::required(['q_user'], $query))
      Response::error_json(['message' => 'Parametros faltantes']);

    $con = DBAppProvider::get_connection();
    $depa_num = $query['depa_num'] ?? null;
    if ($con) {
      $rows_data = Resident::search_user_depa($con, $query['q_user'], $depa_num);
      if ($rows_data) {
        Response::success_json('Residentes ' . $query['q_user'], $rows_data);
      } else {
        Response::success_json('Sin resultados', $rows_data, 200);
      }
    } else {
      Response::error_json(['message' => 'Error, token no detectado'], 500);
    }
  }
  public function get_residents($query) {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $residents = Resident::all_residents($con);
      Render::view('resident/list', ['residents' => $residents]);
    }
  }
  public function get_user($query) {
    if (!Request::required(['pin', 'user_id'], $query))
      Response::error_json(['message' => 'Parametros faltantes']);

    $condominio = Accesos::getCondominio($query['pin']);
    $con = Database::getInstanceByPin($query['pin']);
    $user = new User($con, $query['user_id']);
    unset($user->password);
    Response::success_json('Usuario', ['user' => $user, 'condominio' => $condominio]);
  }
  public function newpass($body) /*protected*/ {
    if (!Request::required(['old', 'new'], $body))
      Response::error_json(['message' => 'Campos requeridos'], 400);

    $user_id = DBAppProvider::get_payload_value('user_id');
    $con = DBAppProvider::get_connection();
    $user = new User($con, $user_id);
    if ($user->id_user > 0) {
      if ($user->password == hash('sha256', $body['old'])) {
        if ($user->newPass($body['new'])) {
          Response::success_json('Contraseña cambiada', []);
        } else
          Response::error_json(['message' => 'No se pudo cambiar la contraseña'], 400);
      } else
        Response::error_json(['message' => 'Contraseña anterior incorrecta'], 400);
    } else
      Response::error_json(['message' => 'Usuario no asociado al token'], 404);
  }
  public function upload_photo($body, $files)/*protected */ {
    $condominio = DBAppProvider::get_enterprise();
    $user_id = DBAppProvider::get_payload_value('user_id');
    $con = DBAppProvider::get_connection();
    $user = new User($con, $user_id);
    if (!isset($files['photo']))
      Response::error_json(['message' => 'Foto requerida <<photo>>'], 400);

    $tipos_permitidos = array("jpeg", "png", "jpg");
    $tipo = strtolower(pathinfo($files['photo']['name'], PATHINFO_EXTENSION));
    if (!in_array($tipo, $tipos_permitidos))
      Response::error_json(['message' => 'Tipo de archivo no permitido'], 400);

    if ($user->id_user == 0)
      Response::error_json(['message' => 'Usuario no encontrado'], 400);

    if ($user->addphoto($files['photo'], $condominio['pin'], $condominio['name'])) {
      unset($user->password);
      Response::success_json('Foto actualizada', ['user' => $user]);
    } else
      Response::error_json(['message' => 'No se pudo actualizar la foto'], 400);
  }
  public function update_data($body)/*protected*/ {
    $con = DBAppProvider::get_connection();
    $user_id = DBAppProvider::get_payload_value('user_id');
    $pin = DBAppProvider::get_enterprise()['pin'];
    $user = new User($con, $user_id);
    if ($user->id_user == 0) Response::error_json(['message' => 'Usuario no encontrado', 'user' => $user], 400);

    $cellphone = $body['cellphone'] ?? $user->cellphone;
    $exist = User::usernameExist($cellphone, $pin, "AND id_user != $user->id_user");
    if ($exist) Response::error_json(['message' => 'El celular ya está registrado'], 400);

    $user->first_name = $body['first_name'] ?? $user->first_name;
    $user->last_name = $body['last_name'] ?? $user->last_name;
    $user->cellphone = $cellphone;
    $user->gender = $body['gender'] ?? $user->gender;
    if ($user->save() > 0) {
      unset($user->password);
      Response::success_json('Datos actualizados', ['user' => $user]);
    } else
      Response::error_json(['message' => 'No se pudo actualizar los datos'], 400);
  }

  public function changepass($body)/*web*/ {
    if (!Request::required(['pass', 'newPass'], $body))
      Response::error_json(['message' => 'Campos requeridos'], 400);

    $user = DBWebProvider::session_get_user();
    if ($user->id_user == 0) Response::error_json(['message' => 'Login fallido, recarge la pagina'], 200);

    $con = DBWebProvider::getSessionDataDB();
    $userNew = new User($con, $user->id_user);
    if ($userNew->password == hash('sha256', $body['pass'])) {
      if ($userNew->newPass($body['newPass'])) {
        Response::success_json('Contraseña cambiada', []);
      } else Response::error_json(['message' => 'No se pudo cambiar la contraseña'], 200);
    } else
      Response::error_json(['message' => 'Contraseña anterior incorrecta'], 200);
  }
}
