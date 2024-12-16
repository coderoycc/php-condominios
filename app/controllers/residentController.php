<?php

namespace App\COntrollers;

use App\Config\Database;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\User;
use App\Providers\DBAppProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;
use ElephantIO\Client;
use function App\Services\event;
use function App\Services\email;

class ResidentController {
  public function me($query) /*protected*/ {
    $con = DBAppProvider::get_connection();
    $id = DBAppProvider::get_payload_value();
    $resident = new Resident($con, $id);
    unset($resident->password);
    unset($resident->device_id);
    if ($resident->role == 'resident') {
      $resident->department();
      $resident->subscription();
      $condominio = DBAppProvider::get_enterprise();
      unset($condominio['pin']);
      unset($condominio['dbname']);
      $cantidad = Subscription::get_users_connected($con, $resident->subscription->id_subscription);
      Response::success_json('Resident data', ['resident' => $resident, 'condominio' => $condominio, 'cantidad_sub' => $cantidad]);
    } else
      Response::error_json(['message' => 'Usuario no asociado'], 400);
  }
  public function forgottenpass($body, $file = null)/*protected*/ {
    if (!Request::required(['email'], $body))
      Response::error_json(['message' => 'Un correo electrónico es necesario para poder restablecer su contraseña'], 400);

    $con = DBAppProvider::get_connection();
    $id = DBAppProvider::get_payload_value();
    $resident = new Resident($con, $id);
    if ($resident->role != 'resident')
      Response::error_json(['message' => 'El usuario no es un residente'], 400);

    $resident->email = $body['email'];
    $resident->change_email();
    $event = event()->forgottenpass($con, $resident->id_user);
    if ($event->id) {
      $res = email()->send('El código para restablecer su contraseña es: <b>' . $event->token . '</b>', true, $body['email'], 'Recuperar constraseña');
      if ($res) {
        Response::success_json('Correo enviado', ['event' => $event]);
      } else {
        Response::error_json(['message' => 'Error al enviar el correo'], 400);
      }
    } else
      Response::error_json(['message' => 'Error al crear el evento'], 400);
  }
  public function verify_token($body, $files = null) /*protected*/ {
    if (!Request::required(['token'], $body))
      Response::error_json(['message' => 'El token es necesario'], 400);

    $con = DBAppProvider::get_connection();
    $id = DBAppProvider::get_payload_value();
    $response = event()->verify($con, $id, $body['token']);
    if ($response['success']) {
      Response::success_json('Token verificado', []);
    } else
      Response::error_json(['message' => $response['message']], 400);
  }
  public function newpass($body, $files = null)/*protected*/ {
    if (!Request::required(['password'], $body))
      Response::error_json(['message' => 'La nueva contraseña es necesaria'], 400);

    $con = DBAppProvider::get_connection();
    $id = DBAppProvider::get_payload_value();
    $user = new User($con, $id);
    if ($user->id_user > 0) {
      if ($user->newPass($body['password'])) {
        Response::success_json('Contraseña cambiada', []);
      } else
        Response::error_json(['message' => 'No se pudo cambiar la contraseña'], 400);
    } else
      Response::error_json(['message' => 'Usuario no asociado al token'], 404);
  }

  public function reset_session($body)/*web master*/ {
    if (!Request::required(['id', 'key'], $body))
      Response::error_json(['message' => 'El id y la llave son necesarios'], 400);

    $con = Database::getInstanceByPin($body['key']);
    $user = new User($con, $body['id']);
    if ($user->id_user > 0) {
      $user->assigned_code = 0;
      $user->update_code_phone();
      Response::success_json('Sesión reiniciada', ['detail' => 'Vuelva a iniciar la sesion']);
    } else
      Response::error_json(['message' => 'Usuario no asociado'], 200);
  }
}
