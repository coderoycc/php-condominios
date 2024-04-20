<?php
if (!isset($_COOKIE['user_obj'])) {
  header('Location: ./auth/login.php');
  die();
} else {
  header('Location: ./dash');
  die();
}
