<?php
$existeDato = $_GET['rq'] ?? 'NO';
if ($existeDato == 'NO') {
  include_once('not_found.php');
} else {
  // Paramteros 0 => empresa sha256, 1 => idEnvio
  $params = explode('_', $existeDato); // Valores a exportar
  if (count($params) == 2) {
    include_once('envio.php');
  } else {
    include_once('not_found.php');
    die();
  }
}
