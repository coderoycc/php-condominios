<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Department;
use App\Models\Resident;
use App\Models\User;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

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
  public function changepass($data, $files = null) {
    $id = $data['idUsuario'];
    $pass = $data['pass'];
    $new = $data['newPass'];
    $usuario = new User($id);
    if ($usuario->id_user == 0) {
      echo json_encode(array('status' => 'error', 'message' => 'No existe el usuario | idUsuario incorrecto'));
    } else if ($usuario->password != hash('sha256', $pass)) {
      echo json_encode(array('status' => 'error', 'message' => 'La contraseña anterior es incorrecta'));
    } else {
      $res = $usuario->newPass($new);
      if ($res > 0) {
        echo json_encode(array('status' => 'success', 'message' => 'La contraseña fue cambiada exitosamente'));
      } else {
        echo json_encode(array('status' => 'error', 'message' => 'Ocurrió un error al cambiar la contraseña, intenta más tarde'));
      }
    }
  }

  // public function changecolor($data, $files = null) {
  //   $id = $data['idUsuario'];
  //   $color = $data['color'];
  //   $user = new Usuario($id);
  //   if ($user->idUsuario != 0 && $color != '') {
  //     $user->color = $color;
  //     $res = $user->save();
  //     if ($res > 0) {
  //       echo json_encode(['status' => 'success', 'message' => 'Cambio correcto']);
  //     } else {
  //       echo json_encode(['status' => 'error', 'message' => 'Error inesperado']);
  //     }
  //   } else {
  //     echo json_encode(['status' => 'error', 'message' => 'No de puede guardar, datos faltantes']);
  //   }
  // }

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

    $con = DBAppProvider::get_conecction();
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
}
