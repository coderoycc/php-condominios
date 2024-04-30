<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ../auth/login.php');
  die();
}
$user = json_decode($_SESSION['user']);
$condominio = json_decode($_SESSION['credentials']);
