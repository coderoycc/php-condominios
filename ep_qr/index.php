<?php

use const App\Config\PASSWORD_SSL;
use const App\Config\PWD_1;
use const App\Config\URL_CERT_PFX;
use const App\Config\URLBASE_API_QR;
use const App\Config\USER_1;

require_once(__DIR__ . '/../app/config/constants.php');
// URL de la API a la que deseas hacer la petición
$url = URLBASE_API_QR . '/Generated';

// Inicializar cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_VERBOSE, true);

// Configurar opciones de cURL
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSLCERTTYPE, "P12");
curl_setopt($curl, CURLOPT_SSLCERT, URL_CERT_PFX);
curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_3);
// var_dump(URL_CERT_PFX);
// var_dump(PASSWORD_SSL);
// if (file_exists(URL_CERT_PFX)) {
//   echo 'existe CERTIFICADO PFX';
// }
curl_setopt($curl, CURLOPT_SSLCERTPASSWD, PASSWORD_SSL);

// Verificar cadena de CA
curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . '/../app/config/crt.pem');
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);


// $pfxPassword = PASSWORD_SSL;

curl_setopt(
  $curl,
  CURLOPT_HTTPHEADER,
  [
    'Content-Type: application/json',
    'Correlation-Id:7854-74585-OPO99'
  ]
);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, USER_1 . ':' . PWD_1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');



// curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $pfxPassword);
$body = '{
  "appUserId":"SMARTUser01112024",
  "currency":"BOB",
  "amount":115.11,
  "gloss":"Pago qr test",
  "serviceCode":"050",
  "businessCode":"0327",
  "singleUse":false,
  "city":"LA PAZ",
  "phoneNumber":"25478785",
  "enableBank":"ALL",
  "teller":"Caja 1",
  "branchOffice":"Condominio Santa Cruz",
  "publicToken":"C3AD15DB-7D0B-43C1-BE6B-9724A9780805",
  "expiration":"1/00:00",
  "collectors":[
      {
        "name":"pay_id",
        "parameter":"number",
        "value":23
      },
      {
        "name":"key",
        "parameter":"string",
        "value":"bar3"
      }
    ]
  }';

// convertir el body a array
$body = json_decode($body, true);

curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

// Ejecutar la petición
$response = curl_exec($curl);

// Comprobar si hubo algún error
if (curl_errno($curl)) {
  echo 'Curl error: ' . curl_error($curl);
} else {
  echo 'Respuesta de la API: ' . $response;
}

// Cerrar la sesión de cURL
curl_close($curl);
