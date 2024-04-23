<?php

namespace App\Controllers;

use App\Models\Condominius;
use App\Models\Department;
use Helpers\Resources\Response;

class RegisterController {
  public function searchCondominiums($query) {
    $condominiums = Condominius::search($query['q'] ?? '');
    Response::success_json(['data' => $condominiums], 200);
  }
  public function searchDepartments($query) {
    $departments = Department::search($query['q'] ?? '', $query['bname']);
    Response::success_json(['data' => $departments], 200);
  }
}
