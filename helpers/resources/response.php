<?php

namespace Helpers\Resources;

class Response {

  public static function success_json(string $message, array $data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    $arr = ['success' => true, 'data' => $data, 'message' => $message];
    echo json_encode($arr);
    die();
  }

  public static function error_json(array $data, $statusCode = 400) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    $arr = ['success' => false];
    $data = array_merge($arr, $data);
    echo json_encode($data);
    die();
  }

  public static function html(string $html, $statusCode = 200) {
    header('Content-Type: text/html');
    http_response_code($statusCode);
    // if(strpos($html, 'Notice: ') !== false && strpos($html, 'Warning: ') !== false){
    //   echo $html;
    //   die();
    // }
    echo $html;
    die();
  }
}
