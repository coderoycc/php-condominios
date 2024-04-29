<?php

namespace Helpers\Middlewares;

require_once(__DIR__ . '/../jwt/JWT.php');

use Helpers\JWT\JWT;

class AuthMiddleware {
  static array $free_routes = [
    'auth/login_app', 
    'register/searchCondominiums', 
    'register/searchDepartments', 
    'register/resident', 
    'subscription/types'
  ];
  public static function check_jwt($route) {
    if (!in_array($route, self::$free_routes)) {
      if (isset($_COOKIE['jwt']))
        $token = $_COOKIE['jwt'];
      else { // headers
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
          $bearer = explode(' ', $headers['Authorization']);
          $token = $bearer[1];
        } else
          $token = null;
      }
      $response = JWT::decode($token);
      if (isset($response['error'])) {
        http_response_code(401);
        echo json_encode(['error' => $response['error']]);
        die();
      } else {
        return $response;
      }
    } else
      return null;
  }
}
