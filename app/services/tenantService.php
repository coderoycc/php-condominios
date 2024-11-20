<?php

namespace App\Services;

use App\Utils\Multitenant\Manager;

class TenantService {
  public function new($name, $pin, $address, $city, $country, $phone, $enable_qr) {
    $response = Manager::create($name, $pin, $address, $city, $country, $enable_qr);
    return $response;
  }
}

if (!function_exists('tenant')) {
  function tenant() {
    return new TenantService();
  }
}
