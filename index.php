<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ./auth/login.php');
  die();
} else {
  header('Location: ./dash');
  die();
}
