<?php
session_start();
date_default_timezone_set('America/La_Paz');
/*********************************
 * importaciones de configuraciones
 *********************************/
require_once 'config/accesos.php';
require_once 'config/database.php';
require_once 'config/constants.php';


/************************
 * Importacion de helpers
 ************************/
require_once '../helpers/resources/dates.php';
require_once '../helpers/middlewares/jwt_identify.php';
require_once '../helpers/resources/response.php';
require_once '../helpers/resources/request.php';

/***********************
 * Importacion de controladores individules
 ***********************/
require_once './controllers/registerController.php';
require_once './controllers/authController.php';


/***********************
 * Importacion de utils
 ************************/
require_once './utils/queries/QueryBuilder.php';
require_once './utils/files/file.handler.php';
require_once './utils/multitenant/manager.php';



/*********************************
 * Entidades que tienen MODELO y CONTROLADOR
 * Agregar nombre de la entidad y crear los archivos en models y controllers
 * @var array $entidades
 *********************************/
$entidades = ['user', 'resident', 'department', 'subscriptiontype', 'subscription', 'condominius', 'locker', 'payment', 'notification', 'services', 'master', 'servicesPay', 'ads', 'shipping', 'company', 'subscriptionCompany', 'logevent'];
foreach ($entidades as $entidad) {
  require_once("models/" . $entidad . ".php");
  require_once("controllers/" . $entidad . "Controller.php");
}


/****************************************
 * Providers similar a utils, parte importante del sistema (core)
 * @var array $providers
 *************************************** */
$providers = ['auth', 'db_', 'logger'];
foreach ($providers as $provider) {
  require_once("providers/" . $provider . "Provider.php");
}

/****************************************
 * Servicios para que se integren con varios modelos 
 * @var array $services
 **************************************/
$services = ['pay', 'subscription', 'tenant', 'email', 'event'];
foreach ($services as $service) {
  require("services/" . $service . "Service.php");
}
