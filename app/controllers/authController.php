<?php

namespace App\Controllers;

use App\Models\Usuario;

class Auth {
  public function login_app($data, $files = null) {
    if (isset($data['user']) && isset($data['password']) && isset($data['pin'])) {
      Usuario::exist($data['user'], $data['password'], $data['pin']);
    } else {
    }
  }
}
