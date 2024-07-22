<?php
// EndPoint para manejar la base master de condominios.
namespace App\Controllers;

use App\Models\Master;
use App\Providers\DBAppProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class MasterController {
  public function add_ad() {
  }
  public function add_service_name($body, $files = null) {
    if (!Request::required(['name', 'acronym'], $body))
      Response::error_json(['message' => 'Parámetros faltantes'], 200);

    if (Master::create_service_name($body))
      Response::success_json('Nombre de servicio agregado', [], 200);
    else
      Response::error_json(['message' => 'Error al agregar el nombre de servicio'], 200);
  }
  public function update_service_name($body) {
    if (!Request::required(['id', 'name', 'acronym'], $body))
      Response::error_json(['message' => 'Parámetros faltantes'], 200);

    if (Master::update_service_name($body))
      Response::success_json('Nombre de servicio actualizado', [], 200);
    else
      Response::error_json(['message' => 'Error al actualizar el nombre de servicio'], 200);
  }
  public function delete_nameservice($body) {
    if (!Request::required(['id'], $body))
      Response::error_json(['message' => 'Parámetros faltantes'], 200);

    if (Master::delete_service_name(intval($body['id'])))
      Response::success_json('Nombre de servicio eliminado', [], 200);
    else
      Response::error_json(['message' => 'Error al eliminar el nombre de servicio'], 200);
  }
  public function list_service_names(array $query) {
    $type = $query['type'] ?? 'app';
    $web = $type == 'web' ? true : false;
    $services = Master::get_services_names();
    if ($web)
      Render::view('master/list_nameservices', ['services' => $services]);
    else
      Response::success_json('Nombres de servicios', ['services' => $services], 200);
  }
  public function get_countries($query) /* protected */ {
    $countries = Master::get_countries(['search' => $query['q'] ?? '']);
    $location = DBAppProvider::get_enterprise();
    unset($location['pin']);
    unset($location['dbname']);
    Response::success_json('Paises', ['countries' => $countries, 'current_location' => $location], 200);
  }
}
