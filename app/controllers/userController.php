<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Resident;
use App\Models\User;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class UserController {
  public function create($data, $files) {
    $usuario = new User();
    $usuario->username = $data['user'];
    $usuario->password = hash('sha256', $data['usuario']);
    // print_r($usuario);
    $usuario->role = $data['rol'];
    $usuario->first_name = $data['nombre'];
    $res = $usuario->save();
    // echo $res . '-----';
    if ($res) {
      echo json_encode(array('status' => 'success'));
    } else {
      echo json_encode(array('status' => 'error'));
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

  public function delete($data) {
    $id = $data['idUsuario'];
    $usuario = new User($id);
    if ($usuario->id_user == 0) {
      echo json_encode(array('status' => 'error', 'message' => 'No existe el usuario | idUsuario incorrecto'));
    } else {
      $res = $usuario->delete();
      if ($res > 0) {
        echo json_encode(array('status' => 'success', 'message' => 'El usuario fue eliminado exitosamente'));
      } else {
        echo json_encode(array('status' => 'error', 'message' => 'Ocurrió un error al eliminar el usuario, intenta más tarde'));
      }
    }
  }
  public function update($data) {
    $idUsuario = $data['idUsuario'];
    $user = $data['usuario'];
    $rol = $data['rol'];
    $usuario = new User($idUsuario);
    if ($usuario->id_user == 0) {
      echo json_encode(array('status' => 'error', 'message' => 'No existe el usuario | idUsuario incorrecto'));
    } else {
      $usuario->username = $user;
      $usuario->role = $rol;
      $usuario->first_name = $data['nombre'];
      $res = $usuario->save();
      if ($res > 0) {
        echo json_encode(array('status' => 'success', 'message' => 'El usuario fue actualizado exitosamente'));
      } else {
        echo json_encode(array('status' => 'error', 'message' => 'Ocurrió un error al actualizar el usuario, intenta más tarde'));
      }
    }
  }

  public function resetPass($data) {
    $id = $data['idUsuario'];
    $usuario = new User($id);
    if ($usuario->id_user == 0) {
      echo json_encode(array('status' => 'error', 'message' => 'No existe el usuario | id_user incorrecto'));
    } else {
      $usuario->password = hash('sha256', $usuario->username);
      $res = $usuario->save();
      if ($res > 0) {
        echo json_encode(array('status' => 'success', 'message' => 'La contraseña fue cambiada exitosamente'));
      } else {
        echo json_encode(array('status' => 'error', 'message' => 'Ocurrió un error al cambiar la contraseña, intenta más tarde'));
      }
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
}
