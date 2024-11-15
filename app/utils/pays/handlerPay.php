<?php

namespace App\Utils;

require_once(__DIR__ . '/../../providers/qrDataProvider.php');

use App\Models\Payment;
use App\Providers\QrDataProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use const App\Config\EXPIRATION_QR;
use const App\Config\PWD_1;
use const App\Config\URLBASE_API_QR;
use const App\Config\USER_1;

/**
 * Clase que maneja las credenciales de pago de la aplicacion todo se ejecuta con CURL, este maneja una instancia
 */
class HandlerPays {
  private $client = null;
  private $endpoint_base = '/Web_ApiQr/api/v4/Qr';
  private $account = null;
  private $body = null;
  private $options = null;
  public function __construct($account) {
    $this->account = $account;
    $data = new QrDataProvider($account);
    $this->client = new Client([
      'base_uri' => URLBASE_API_QR,
      'cert' => $data->get_cert(),
      'verify' => false // ignorar verificacion
    ]);
  }
  /**
   * 
   * @param Payment $payment
   * @param array $condominio
   * @param string $account
   * @param boolean $singleuse
   * @param boolean $collector datos a enviar para recibir en el rollback
   * @return HandlerPays
   */
  public function load($payment, $condominio, $singleuse = true) {
    // obtener variables de acuerdo a la cuenta
    $data_body = new QrDataProvider($payment->account);
    $collector = [
      [
        "name" => "pay_id",
        "parameter" => "number",
        "value" => $payment->idPayment
      ],
      [
        "name" => "key",
        "parameter" => "string",
        "value" => $condominio['pin']
      ]
    ];
    $this->body = array_merge([
      'currency' => $payment->currency,
      'amount' => $payment->amount,
      'gloss' => $payment->gloss,
      'ServiceCode' => '050',
      'singleUse' => $singleuse,
      'enableBank' => 'ALL',
      'city' => $condominio['city'],
      "branchOffice" => 'Condominio ' . $condominio['name'],
      "teller" => "Caja 1",
      'phoneNumber' => $condominio['phone'] ?? '000000',
      'expiration' => EXPIRATION_QR,
      'collectors' => $collector
    ], $data_body->get_body());
    // var_dump($this->body);
    $this->options = [
      'headers' => [
        'Content-Type' => 'application/json', // Indicar que el contenido es JSON
        'Accept' => '*',      // Esperar respuesta en JSON
        'Correlation-Id' => $payment->correlation_id
      ],
      'json' => $this->body, // Serializa automáticamente $data a JSON,
      'auth' => [USER_1, PWD_1]
    ];

    return $this;
  }
  public function pay() {
    $response = [];
    try {
      $response = $this->client->post($this->endpoint_base . '/Generated', $this->options);
      $response = json_decode($response->getBody(), true);
    } catch (RequestException $e) {
      // Capturar el error y obtener detalles
      $res_error = $e->getResponse(); // Obtener la respuesta completa de la excepción
      if ($res_error) {
        // var_dump($res_error);
        $body = $res_error->getBody();
        // echo "Error Status Code: " . $res_error->getStatusCode() . "\n";
        $response = json_decode($body, true);
      }
    }
    return $response;
  }
}
