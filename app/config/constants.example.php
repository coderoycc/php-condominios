<?php

namespace App\Config;

const PRODUCTION     = false;
const SHOW_ERRORS    = false; // muestra errores en logger provider y (WRNING - NOTICE) de PHP en index.php
const URLBASE_API_QR = 'https://sandbox.openbanking.bcp.com.bo/Web_ApiQr/api/v4/Qr';
const BUSINESS_CODE  = '';
const SERVICE_CODE   = '';
const APP_USER_ID_1  = '';
const PUBLIC_TK_USER_1 = '';
const PASSWORD_SSL      = '';

const USER_1 = '';
const PWD_1  = '';
const URL_CERT = __DIR__ . '/cert.pem';
const URL_CERT_PFX = __DIR__ . '/BCP_SANDBOX.pfx';

const EXPIRATION_QR  = '01/00:00'; // UN DIA 



const MAIL_PWD    = '';
const MAIL_PORT   = 587;
const MAIL_FROM   = '';

const URL_WEBSOCKET = 'http://localhost';
const WEBSOCKET_PORT = 3000;
