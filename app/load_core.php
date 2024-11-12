<?php
session_start();
date_default_timezone_set('America/La_Paz');
require_once 'config/accesos.php';
require_once 'config/database.php';
require_once 'config/constants.php';
require_once '../helpers/resources/dates.php';
require_once '../helpers/middlewares/jwt_identify.php';
require_once '../helpers/resources/response.php';
require_once '../helpers/resources/request.php';
require_once './controllers/registerController.php';
require_once './controllers/authController.php';
require_once './utils/queries/QueryBuilder.php';
require_once './utils/files/file.handler.php';
$entidades = ['user', 'resident', 'department', 'subscriptiontype', 'subscription', 'condominius', 'locker', 'payment', 'notification', 'services', 'master', 'servicesPay', 'ads', 'shipping', 'company', 'subscriptionCompany'];
foreach ($entidades as $entidad) {
  require_once("models/" . $entidad . ".php");
  require_once("controllers/" . $entidad . "Controller.php");
}

$providers = ['auth', 'db_'];
foreach ($providers as $provider) {
  require_once("providers/" . $provider . "Provider.php");
}
$services = ['pay', 'subscription'];
foreach ($services as $service) {
  require("services/" . $service . "Service.php");
}
