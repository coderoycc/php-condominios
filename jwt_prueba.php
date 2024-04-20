<?php
require_once('./authentication/middlewares/JWT.php');

use Auth\Middleware\JWT;

$usuario = $_POST['usuario'];
$idUsuario = 12;
$domain = 'lucecita';
$payload = [
  'usuario_id' => $idUsuario,
  'dominio' => $domain,
  'usuario' => $usuario,
  'exp' => time() + (60 * 1) // El token expirará en 1 hora
];

$token = JWT::encode($payload);
echo $token;
