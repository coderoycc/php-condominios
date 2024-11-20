<?php

namespace App\COntrollers;

use App\Models\Department;
use App\Models\Resident;
use App\Models\Subscription;
use App\Models\User;
use App\Providers\DBAppProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

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
}
