<?php
require_once('./authentication/middlewares/JWT.php');

use Auth\Middleware\JWT;

$header = getallheaders();
$bearer = explode(' ', $header['Authorization']);

var_dump($bearer);

$token = $bearer[1];
echo "\n";
http_response_code(403);
$datos = JWT::decode($token);
var_dump($datos);
