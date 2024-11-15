<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use const App\Config\PWD_1;
use const App\Config\USER_1;

class ServicesPayController {
  public function xd() {
    $pfxFile = __DIR__ . "/../config/cert.pem";

    $client = new Client([
      'base_uri' => 'https://sandbox.openbanking.bcp.com.bo',
      'cert' => $pfxFile, // Configura el certificado PFX
      'verify' => false // Ignorar la verificación del certificado del servidor
    ]);

    $collector = [
      [
        "name" => "pay_id",
        "parameter" => "number",
        "value" => 12
      ],
      [
        "name" => "key",
        "parameter" => "string",
        "value" => "asss"
      ]
    ];
    $body = [
      'appUserId' => 'SMARTUser01112024',
      "BusinessCode" => "0327",
      "publicToken" => "C3AD15DB-7D0B-43C1-BE6B-9724A9780805",
      'currency' => "BOB",
      'amount' => 456.90,
      'gloss' => "Test asd",
      'serviceCode' => "050",
      "singleUse" => true,
      'enableBank' => 'ALL',
      'city' => "LA PAZ",
      "branchOffice" => 'Condominio La Paz',
      "teller" => "Caja 1",
      'phoneNumber' => '000000',
      'expiration' => "01/00:00",
      'collectors' => $collector
    ];

    $options = [
      'headers' => [
        'Content-Type' => 'application/json', // Indicar que el contenido es JSON
        'Accept' => '*',      // Esperar respuesta en JSON
        'Correlation-Id' => '198PE-345ER-7EFE88'
      ],
      'json' => $body, // Serializa automáticamente $data a JSON,
      'auth' => [USER_1, PWD_1]
    ];

    try {
      $response = $client->post('/Web_ApiQr/api/v4/Qr/Generated', $options);
      // $response = $client->request('POST', '/Web_ApiQr/api/v4/Qr/Generated');
      $jsonResponse = $response->getBody()->getContents();
      echo $jsonResponse;
    } catch (RequestException $e) {
      // Capturar el error y obtener detalles
      $response = $e->getResponse(); // Obtener la respuesta completa de la excepción

      if ($response) {
        var_dump($response);
        // Si la respuesta está disponible, obtener el cuerpo completo
        $body = $response->getBody();
        echo "Error Status Code: " . $response->getStatusCode() . "\n";
        echo "Error Response Body: " . $body . "\n"; // Mostrar el cuerpo de la respuesta
      } else {
        // Si no hay respuesta (por ejemplo, problemas de red)
        echo "Error: " . $e->getMessage() . "\n";
      }
    }
  }
}
