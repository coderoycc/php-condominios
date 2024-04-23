<?php
date_default_timezone_set('America/La_Paz');
require_once 'config/accesos.php';
require_once 'config/database.php';
require_once '../helpers/middlewares/jwt_identify.php';
require_once '../helpers/resources/response.php';
require_once './controllers/registerController.php';
require_once './controllers/authController.php';
$entidades = ['user', 'department', 'subscription', 'condominius'];
foreach ($entidades as $entidad) {
  require_once("models/" . $entidad . ".php");
  require_once("controllers/" . $entidad . "Controller.php");
}
