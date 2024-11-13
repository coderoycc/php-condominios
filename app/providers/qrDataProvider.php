<?php

namespace App\Providers;

use const App\Config\APP_USER_ID_1;
use const App\Config\BUSINESS_CODE;
use const App\Config\PUBLIC_TK_USER_1;
use const App\Config\PWD_1;
use const App\Config\URL_CERT;
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
  public function __construct($account) {
    if ($account == '1') {
      $this->body = $this->account1;
      $this->auth = USER_1 . ':' . PWD_1;
      $this->urlcert = URL_CERT;
    } else {
      $this->body = $this->account2;
      $this->auth = USER_1 . ':' . PWD_1;
      $this->urlcert = URL_CERT;
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
}
