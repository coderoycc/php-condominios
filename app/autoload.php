<?php
$entidades = ['usuario', 'envio'];
foreach ($entidades as $entidad) {
  require_once("models/" . $entidad . ".php");
  require_once("controllers/" . $entidad . "Controller.php");
}
