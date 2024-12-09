<?php

namespace App\COntrollers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Department;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\User;
use App\Providers\DBAppProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

use function App\Utils\email;

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
  public function recover_pass($body)/*protected*/ {
    if (Request::required(['email'], $body))
      Response::error_json(['message' => 'Un correo electrónico es necesario para poder restablecer su contraseña'], 400);

    $con = DBAppProvider::get_connection();
    $id = DBAppProvider::get_payload_value();
    $resident = new Resident($con, $id);
    if ($resident->role != 'resident')
      Response::error_json(['message' => 'El usuario no es un residente'], 400);

    $resp = '';
  }
  public function test($query) {
    $con = Database::getInstanceByPin('bar1');
    $id = 1;
    $email = 'rcchambi4@gmail.com';
    $resident = new Resident($con, $id);
    if ($resident->role == 'resident') {
      $res = email()->send('Hola desde otra cuenta', false, $email, 'Test nuevo de servicio');
      if ($res) {
        Response::success_json('Resident data', ['resident' => $resident]);
      } else {
        Response::error_json(['message' => 'Error al enviar el correo'], 400);
      }
    }
    Response::error_json(['message' => 'Usuario no asociado a residente'], 400);
  }
}
