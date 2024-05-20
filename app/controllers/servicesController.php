<?php

namespace App\Controllers;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class ServicesController {
  public function add_code_service($body, $files = null) /* protected */{
    if(!Request::required(['service_name', 'code', 'id_sername'], $body))
      Response::error_json(['message' => 'Campos faltantes [service_name, code, id_sername]']);

    
  }
  public function update_service() {
  }
  public function delete_service() {
  }
  public function get_service() {
  }
  public function verifie_service() {
  }
}
