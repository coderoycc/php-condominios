<?php

namespace EPQr;

require_once(__DIR__ . '/../app/models/payment.php');
// require_once(__DIR__ . '/../app/models/');
require_once(__DIR__ . '/../app/config/accesos.php');
require_once(__DIR__ . '/../app/config/database.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$data = file_get_contents('php://input');
$body = json_decode($data, true);

var_dump($body);
