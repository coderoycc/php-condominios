<?php
// EndPoint para manejar la base master de condominios.
namespace App\Controllers;

use App\Config\Database;
use App\Models\LockerContent;
use App\Models\Master;
use App\Models\Subscriptiontype;
use App\Providers\DBAppProvider;
use App\Providers\DBWebProvider;
use App\Utils\Queries\QueryBuilder;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;


class MasterController {
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
  public function support_phone($query) {
    $phone = Master::get_support_phone();
    Response::success_json('Telefono de soporte', ['support_phone' => $phone], 200);
  }
  /**
   * Search residents in all databases
   * @param array $query Params query
   * @return void Response [never] Using class Render
   */
  public function residents($query) {
    $search = $query['q'] ?? '';
    $option = $query['option'] ?? '';

    $sql = new QueryBuilder();
    if ($search == '') {
      $residents = $sql->select('tblUsers', 'a')
        ->leftJoin('tblResidents', 'b', "a.id_user = b.user_id")
        ->leftJoin('tblDepartments', 'c', 'c.id_department = b.department_id')
        ->leftJoin('tblUsersSubscribed', 'd', 'd.user_id = a.id_user')
        ->leftJoin('tblSubscriptions', 'e', 'e.id_subscription = d.subscription_id')
        ->where("a.role = 'resident'")
        ->orderBy('a.created_at DESC')
        ->get('TOP 20 a.id_user, a.first_name, a.last_name, a.username, a.role, a.created_at, a.cellphone, a.gender, a.status as user_state, b.*, c.*, e.* ');
    } else { //filtros
      $condition = self::get_where_filter($option, $search);
      $residents = $sql->select('tblUsers', 'a')
        ->leftJoin('tblResidents', 'b', "a.id_user = b.user_id")
        ->leftJoin('tblDepartments', 'c', 'c.id_department = b.department_id')
        ->leftJoin('tblUsersSubscribed', 'd', 'd.user_id = a.id_user')
        ->leftJoin('tblSubscriptions', 'e', 'e.id_subscription = d.subscription_id')
        ->where("a.role = 'resident' AND (" . $condition . ")")
        ->get('a.id_user, a.first_name, a.last_name, a.username, a.role, a.created_at, a.cellphone, a.gender, a.status as user_state, b.*, c.*, e.*');
    }
    Render::view('resident/master_list', ['residents' => $residents, 'search' => $search, 'option' => $option]);
  }
  public function residents_sub($query) {
    $search = $query['q'] ?? '';
    $type = $query['type'] ?? '';
    $option = '';
    // obtener la conexion del condominio 
    $con = DBWebProvider::getSessionDataDB();
    $types = Subscriptiontype::getTypes(null, $con, true);

    if ($type == 'Sin Suscripción') {
      $option = "AND e.id_subscription IS NULL AND c.id_department IS NOT NULL";
    } else if ($type != '') {
      $option = "AND f.name LIKE '" . $type . "'";
    }
    // $types = ['Sin Suscripción', 'Gratuito', 'Standard', 'Premium', 'Premium VIP'];

    $sql = new QueryBuilder();
    $residents = [];
    $residents = $sql->select('tblUsers', 'a')
      ->leftJoin('tblResidents', 'b', "a.id_user = b.user_id")
      ->leftJoin('tblDepartments', 'c', 'c.id_department = b.department_id')
      ->leftJoin('tblUsersSubscribed', 'd', 'd.user_id = a.id_user')
      ->leftJoin('tblSubscriptions', 'e', 'e.id_subscription = d.subscription_id')
      ->leftJoin('tblSubscriptionType', 'f', 'e.type_id = f.id_subscription_type')
      ->where("a.role = 'resident' $option AND (a.first_name LIKE '%$search%' OR a.last_name LIKE '%$search%')")
      ->orderBy('a.created_at DESC')
      ->get('TOP 80 a.id_user, a.first_name, a.last_name, a.username, a.role, a.created_at, a.cellphone, a.gender, a.status as user_state, b.*, c.*, e.*, f.name as type_sub');

    Render::view('subscription/list_global', ['search' => $search, 'types_sub' => $types, 'type_selected' => $type, 'residents' => $residents]);
  }
  public function locker_content($query) {
    if (!Request::required(['id', 'depa_id', 'key'], $query))
      Render::view('error_html', ['message' => 'Parámetros faltantes', 'message_detail' => 'Faltan parametros']);

    $con = Database::getInstanceByPin($query['key']);
    $depa = $query['depa_id'] == '' ? 0 : intval($query['depa_id']);
    $entrada = LockerContent::get_list_department($con, $depa, 'ENTRADA');
    $salida = LockerContent::get_list_department($con, $depa, 'SALIDA');
    Render::view('locker/content_modal', ['entrada' => $entrada, 'salida' => $salida]);
  }

  public function departments_with_sub($query) {
    $query = new QueryBuilder();
    $data = $query->select('tblDepartments', 'a')
      ->leftJoin('tblSubscriptions', 'c', 'c.department_id = a.id_department')
      ->where("c.status = 'VALIDO' AND c.expires_in > GETDATE()")
      ->orderBy('a.dep_number DESC')
      ->get();
    Response::success_json('Departamentos', ['departments' => $data], 200);
  }

  public static function get_where_filter($option, $value) {
    $cad = "";
    $value = trim($value);
    switch ($option) {
      case 'nombres':
        $cad = "a.first_name LIKE '%$value%' OR a.last_name LIKE '%$value%'";
        break;
      case 'celular':
        $cad = "a.cellphone LIKE '%$value%'";
        break;
      case 'email':
        $cad = "a.email LIKE '%$value%'";
        break;
      case 'dpto':
        $cad = "c.dep_number LIKE '%$value%'";
        break;
      default:
        $cad = "a.first_name LIKE '%$value%' OR a.last_name LIKE '%$value%' OR a.cellphone LIKE '%$value%' OR c.dep_number LIKE '%$value%'";
        break;
    }
    return $cad;
  }
}
