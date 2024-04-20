<?php

namespace App;

session_start();
date_default_timezone_set('America/La_Paz');
require_once 'config/database.php';
require_once 'config/accesos.php';

include_once 'autoload.php';
$url = isset($_GET['url']) ? $_GET['url'] : '';

$parts = explode('/', $url);
// print_r($parts);
$method = $_SERVER['REQUEST_METHOD'];
$controller = $parts[0];
$action = $parts[1];
$controllerClass = "App\\Controllers\\" . $controller . "Controller";
$controller = new $controllerClass();

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
