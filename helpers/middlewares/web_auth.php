<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ../auth/login.php');
  die();
}
$condominios = json_decode($_SESSION['condominios'], true);
$user = json_decode($_SESSION['user']);
$condominio = json_decode($_SESSION['credentials']);
// var_dump($condominios);
