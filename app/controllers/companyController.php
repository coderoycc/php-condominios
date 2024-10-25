<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Company;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class CompanyController {
  public function create($body, $files)/*WEB*/ {
    if (!Request::required(['name', 'phone'], $body))
      Response::error_json(['success' => false, 'message' => 'Parametros faltantes'], 200);

    $con = Database::getInstaceCondominios();
    $company = new Company($con);
    $company->company = strtoupper($body['name'] ?? '');
    $company->phone = $body['phone'] ?? '';
    $company->line = $body['line'] ?? '';
    if ($company->insert() > 0) {
      Response::success_json('Empresa agregada', ['company' => $company], 200);
    } else
      Response::error_json(['success' => false, 'message' => 'Error al crear la empresa'], 200);
  }
  public function all($query)/*web*/ {
    $companies = Company::get_companies($query);
    Response::success_json('Empresas', $companies, 200);
  }
  public function all_view($query) {
    $companies = Company::get_companies($query);
    Render::view('company/list_companies', ['companies' => $companies]);
  }
}
