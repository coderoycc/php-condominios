<?php

namespace App;

require_once 'vendor/autoload.php';
include_once 'load_core.php';
// composer "ramsey/uuid"
// require_once 'webSocket/handleSocket.php';
// require_once 'webSocket/server.php';

use Helpers\Middlewares\AuthMiddleware;
use Helpers\Resources\Response;

$url = isset($_GET['url']) ? $_GET['url'] : '';

$parts = explode('/', $url);

$method = $_SERVER['REQUEST_METHOD'];
$controller = $parts[0] ?? 'x';
$action = $parts[1] ?? 'y';
$controllerClass = "App\\Controllers\\" . $controller . "Controller";
try {
  if (!class_exists($controllerClass))
    Response::error_json(array('error' => 'Controlador no encontrado'), 404);
  if (!method_exists($controllerClass, $action))
    Response::error_json(array('error' => 'Metodo no encontrado'), 404);
  $controller = new $controllerClass();

  $payload = AuthMiddleware::check_jwt($url);

  switch ($method) {
    case 'GET':
      $controller->$action($_GET);
      break;
    case 'POST':
      $controller->$action($_POST, $_FILES);
      break;
    case 'PUT':
      parse_str(file_get_contents('php://input'), $params);
      $controller->$action($params);
      break;
    case 'DELETE':
      parse_str(file_get_contents('php://input'), $params);
      $controller->$action($params);
      break;
    default:
      echo json_encode(array('error' => 'Metodo no permitido'));
  }
} catch (\Throwable $th) {
  Response::error_json(array('error' => $th->getMessage(), 'detail' => $th->getFile() . ' 1##-->&&<--##' . $th->getLine()));
}
