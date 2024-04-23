<?php

namespace Helpers\Resources;

class Response {
  
  public static function success_json(array $data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([...$data, 'success' => true]); 
    die();
  }

  public static function error_json(array $data, $statusCode = 400) {
    http_response_code($statusCode);
    echo json_encode([...$data, 'success' => false]);
    die();
  }
}
