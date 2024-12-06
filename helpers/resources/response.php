<?php

namespace Helpers\Resources;

use function App\Providers\logger;

class Response {

  public static function success_json(string $message, $data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    $arr = ['success' => true, 'data' => $data, 'message' => $message];

    $res = json_encode($arr);
    logger()->response($res);
    echo $res;
    die();
  }

  public static function error_json(array $data, $statusCode = 400) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    $arr = ['success' => false];
    $data = array_merge($arr, $data);
    $res = json_encode($data);
    logger()->response($res);
    echo $res;
    die();
  }

  public static function html(string $html, $statusCode = 200) {
    header('Content-Type: text/html');
    http_response_code($statusCode);
    echo $html;
    die();
  }
}
class Render {
  public static function view(string $name_view, array $data): void {
    extract($data);
    logger()->response("view $name_view");
    if (self::view_exist($name_view)) {
      ob_start();
      require_once __DIR__ . '/../../app/views/' . $name_view . '.php';
      $content = ob_get_clean();
    } else {
      $content = '<div class="alert alert-danger">Ocurrio un error, no se pudo cargar la vista.<hr> 
      Error 404: view <b>' . $name_view . '</b></div>';
    }
    Response::html($content);
  }
  public static function view_exist(string $name_view): bool {
    $urlDir = __DIR__ . '/../../app/views/' . $name_view . '.php';
    return file_exists($urlDir);
  }
}
