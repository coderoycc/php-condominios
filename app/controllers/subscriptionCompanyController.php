<?php

namespace App\COntrollers;

use App\Config\Database;
use App\Models\Company;
use App\Models\SubscriptionCompany;
use App\Providers\DBAppProvider;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class SubscriptionCompanyController {
  public function subscribe($body, $files = null)/*protected*/ {
    if (!Request::required(['company', 'company_id'], $body))
      Response::error_json(['message' => 'Datos faltantes']);

    $con = DBAppProvider::get_connection();
    $sub = DBAppProvider::get_sub();
    SubscriptionCompany::addNewSubscription($con, $body['company'], $body['company_id'], $sub->id_subscription);
    Response::success_json('Agregado correctamente', [], 200);
  }
  public function me($query)/*protected*/ {
    $con = DBAppProvider::get_connection();
    $sub = DBAppProvider::get_sub();
    $response = SubscriptionCompany::getMySubscriptions($con, $sub->id_subscription);

    $newCon = Database::getInstaceCondominios();
    $res = [];
    foreach ($response as $key => $value) {
      $company = new Company($newCon, $value['company_id']);
      $mergedData = array_merge($value, ['company' => $company]);
      $res[] = $mergedData;
    }
    Response::success_json('Request correct', $res, 200);
  }
  public function delete($body)/*protected*/ {
    if (!Request::required(['company_id'], $body))
      Response::error_json(['message' => 'Datos faltantes']);

    $con = DBAppProvider::get_connection();
    $sub = DBAppProvider::get_sub();
    SubscriptionCompany::deleteSubscription($con, $body['company_id'], $sub->id_subscription);
    Response::success_json('Eliminado correctamente', [], 200);
  }
}
