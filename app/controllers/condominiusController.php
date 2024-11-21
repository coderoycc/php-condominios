<?php

namespace App\Controllers;

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Condominius;
use App\Models\Master;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

use function App\Services\tenant;

class CondominiusController {
  public function create($body, $files = null)/*web*/ {
    if (!Request::required(['name', 'pin', 'address', 'city'], $body))
      Response::error_json(['message' => 'Parámetros faltantes'], 200);

    if (strlen($body['pin']) < 3 || strlen($body['pin']) > 5)
      Response::error_json(['errors' => [['name' => 'pin', 'error' => 'El pin debe tener entre 3 y 5 caracteres']]], 200);

    if (Condominius::name_exist($body['name']))
      Response::error_json(['errors' => [['name' => 'name', 'error' => 'El nombre de condominio existe']]], 200);

    if (Condominius::pin_exist($body['pin']))
      Response::error_json(['errors' => [['name' => 'pin', 'error' => 'El pin de condominio existe']]], 200);

    $enable_qr = isset($body['qr']) ? true : false;
    $res = tenant()->new($body['name'], $body['pin'], $body['address'], $body['city'], 'Bolivia', '000000', $enable_qr);
    if ($res['state']) {
      $condominioData = Accesos::getCondominio($body['pin']);
      $user = DBWebProvider::session_get_user();
      $condominios = Master::get_condominios("WHERE pin != '" . $body['pin'] . "'");
      DBWebProvider::start_session($user, $condominioData, $condominios);
      Response::success_json('Condominio creado', [], 200);
    } else
      Response::error_json(['message' => $res['message']], 200);
  }
  public function all()/*web*/ {
    $condominios = Condominius::all();
    Render::view('master/condominius_list', ['condominios' => $condominios]);
  }
}
