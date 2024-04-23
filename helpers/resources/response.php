<?php

namespace Helpers\Resources;

class Response {

  public static function success_json(array $data, $statusCode = 200) {
    http_response_code($statusCode);
    $arr = ['success' => true];
    $data = array_merge($arr, $data);
    echo json_encode($data);
    die();
  }

  public static function error_json(array $data, $statusCode = 400) {
    http_response_code($statusCode);
    $arr = ['success' => false];
    $data = array_merge($arr, $data);
    echo json_encode($data);
    die();
  }
}
