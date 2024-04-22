<?php
namespace App;
// session_start();
include_once 'autoload.php';
use App\Config\Accesos;


$url = isset($_GET['url']) ? $_GET['url'] : '';
// var_dump($url); # users/getAll
$parts = explode('/', $url);  
// print_r($parts);
$method = $_SERVER['REQUEST_METHOD'];
$controller = $parts[0];
$action = $parts[1];
$controllerClass = "App\\Controllers\\" . $controller . "Controller";
try {
  if (!class_exists($controllerClass)) {
    echo json_encode(array('error' => 'Controlador no encontrado'));
    exit;
  }
  if (!method_exists($controllerClass, $action)) {
    echo json_encode(array('error' => 'Metodo no encontrado'));
    exit;
  }
  $controller = new $controllerClass();
  $accesos = new Accesos('Cambio de valores'); 
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
  echo json_encode(array('error' => $th->getMessage()));
}

