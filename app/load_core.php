<?php
session_start();
date_default_timezone_set('America/La_Paz');
require_once 'config/accesos.php';
require_once 'config/database.php';
require_once '../helpers/resources/dates.php';
require_once '../helpers/middlewares/jwt_identify.php';
require_once '../helpers/resources/response.php';
require_once '../helpers/resources/request.php';
require_once './controllers/registerController.php';
require_once './controllers/authController.php';

$entidades = ['user', 'department', 'subscription', 'condominius', 'locker'];
foreach ($entidades as $entidad) {
  require_once("models/" . $entidad . ".php");
  require_once("controllers/" . $entidad . "Controller.php");
}

$providers = ['auth','db_web'];
foreach ($providers as $provider) {
  require_once("providers/" . $provider . "Provider.php");
}
// objetos hijos
require_once 'models/resident.php';
