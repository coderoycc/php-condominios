<?php

namespace App\Controllers;

use App\Models\Department;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class DepartmentController {
  public function list_all() /*WEB*/ {
    $con = DBWebProvider::getSessionDataDB();

    $departments = Department::get_all($con);
    Render::view('department/list', ['departments' => $departments]);
  }
  public function create($data, $file = null) {
    if (!Request::required(['descrip', 'bedrooms', 'depa_num'], $data)) {
      Response::error_json(['message' => 'Parámetros faltantes'], 200);
    }
    $con = DBWebProvider::getSessionDataDB();
    $department = new Department($con);
    $department->description = $data['descrip'];
    $department->bedrooms = $data['bedrooms'];
    $department->dep_number = $data['depa_num'];
    if ($department->save() > 0) {
      Response::success_json('Departamento creado', ['department' => $department], 200);
    } else {
      Response::error_json(['message' => 'Error al crear el departamento'], 200);
    }
  }
  public function update($data) /*web*/ {
    if (!Request::required(['id', 'depa_num'], $data))
      Response::error_json(['message' => 'Id faltante'], 200);

    $con = DBWebProvider::getSessionDataDB();
    $department = new Department($con, $data['id']);
    $department->description = $data['descrip'];
    $department->bedrooms = $data['bedrooms'];
    $department->dep_number = $data['depa_num'];
    if ($department->save() > 0)
      Response::success_json('Departamento actualizado', ['department' => $department], 200);
    else
      Response::error_json(['message' => 'Error al actualizar el departamento'], 200);
  }
  public function delete($data) /*web*/ {
    if (!Request::required(['id'], $data))
      Response::error_json(['message' => 'Id faltante'], 200);

    $con = DBWebProvider::getSessionDataDB();
    $department = new Department($con, $data['id']);
    if ($department->id_department == 0)
      Response::error_json(['message' => 'El departamento no existe'], 200);

    if ($department->delete())
      Response::success_json('Departamento eliminado', ['department' => $department], 200);
    else
      Response::error_json(['message' => 'Error al eliminar el departamento'], 200);
  }
  public function content_edit($query) {
    if (!Request::required(['id'], $query))
      Render::view('error_html', ['message' => 'Parámetros faltantes', 'message_details' => 'Falta el id']);
    $con = DBWebProvider::getSessionDataDB();
    $department = new Department($con, $query['id']);
    Render::view('department/edit_content', ['department' => $department]);
  }
  public function subscribed($query)/*web */ {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $subs = Department::get_with_subs($con, $query);
      Render::view('subscription/with_department', ['subs' => $subs]);
    } else
      Render::view('error_html', ['message' => 'Instancia de conexión', 'message_details' => 'Vuelva a iniciar sesion']);
  }
  public function depa_subscription($query) /*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    if ($con) {
      $department = new Department($con, $query['id']);
      if ($department->id_department == 0) {
        Render::view('error_html', ['message' => 'Departamento no encontrado', 'message_details' => 'El departamento no existe']);
      }
      $department->get_subscriptions();
      Render::view('department/content_subs', ['department' => $department]);
    } else
      Render::view('error_html', ['message' => 'Instancia de conexión', 'message_details' => 'Vuelva a iniciar sesion']);
  }
}
