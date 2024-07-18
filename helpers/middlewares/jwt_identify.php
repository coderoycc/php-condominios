<?php

namespace Helpers\Middlewares;

require_once(__DIR__ . '/../jwt/JWT.php');

use Helpers\JWT\JWT;
use Helpers\Resources\Response;

class AuthMiddleware {
  static array $routes = [ // rutas protegidas con JWT
    'user/number',
    'notification/send_by_id',
    'user/search_with_department',
    'locker/add_content',
    'locker/list_all',
    'locker/list_content',
    'services/add_code_service',
    'services/get_my_services',
    'services/delete_my_service',
    'services/update_my_service',
    'services/codes_department_app',
    'services/detail_services_month',
    'locker/change_available',
    'shipping/create',
    'shipping/get',
    'shipping/me'
  ];
  public static function check_jwt($route) {
    if (in_array($route, self::$routes)) {
      if (isset($_COOKIE['jwt']))
        $token = $_COOKIE['jwt'];
      else { // headers
        $headers = getallheaders();

        $header = null;
        if (isset($headers['Authorization'])) {
          $header = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
          $header = $headers['authorization'];
        }
        if ($header) {
          $bearer = explode(' ', $header);
          $token = $bearer[1];
        } else
          $token = null;
      }
      $response = JWT::decode($token);
      if (isset($response['error'])) {
        Response::error_json(['message' => $response['error']], 403);
      } else {
        return $response;
      }
    } else
      return null;
  }
}
