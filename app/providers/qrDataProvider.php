<?php

namespace App\Providers;

use const App\Config\APP_USER_ID_1;
use const App\Config\BUSINESS_CODE;
use const App\Config\PASSWORD_SSL;
use const App\Config\PUBLIC_TK_USER_1;
use const App\Config\PWD_1;
use const App\Config\URL_CERT;
use const App\Config\URL_CERT_PFX;
use const App\Config\USER_1;

class QrDataProvider {
  private array $account1 = [
    'appUserId' => APP_USER_ID_1,
    'businessCode' => BUSINESS_CODE,
    'publicToken' => PUBLIC_TK_USER_1,
  ];

  private array $account2 = [
    'appUserId' => APP_USER_ID_1,
    'businessCode' => BUSINESS_CODE,
    'publicToken' => PUBLIC_TK_USER_1,
  ];
  private array $body = [];
  private string $auth = '';
  private string $urlcert = '';
  private string $certpass = '';
  private string $pfxfile = '';
  public function __construct($account) {
    if ($account == '1') {
      $this->body = $this->account1;
      $this->auth = USER_1 . ':' . PWD_1;
      $this->urlcert = URL_CERT;
      $this->pfxfile = URL_CERT_PFX;
      $this->certpass = PASSWORD_SSL;
    } else {
      $this->body = $this->account2;
      $this->auth = USER_1 . ':' . PWD_1;
      $this->urlcert = URL_CERT;
      $this->certpass = PASSWORD_SSL;
      $this->pfxfile = URL_CERT_PFX;
    }
    // $this->loadpem();
  }
  protected function loadpem() {
    $contenidoPfx = file_get_contents($this->pfxfile);
    if (isset($contenidoPfx)) {
      openssl_pkcs12_read($contenidoPfx, $vectorPEM, $this->certpass);
      $contenidoPem = $vectorPEM["cert"] . $vectorPEM["pkey"];
      file_put_contents(URL_CERT, $contenidoPem);
    }
  }
  public function get_body() {
    return $this->body;
  }
  public function get_auth() {
    return $this->auth;
  }
  public function get_cert() {
    return $this->urlcert;
  }
  public function get_cert_pass() {
    return $this->certpass;
  }
}
