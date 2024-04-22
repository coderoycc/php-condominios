<?php

namespace Helpers\Resources;

class Response {
  public int $statusCode;
  public bool $status;
  public mixed $data;
  public string $message;

  public string $html = '';

  public function __construct($status = false, int $statusCode = 200, mixed $data = [], string $message = '') {
    $this->statusCode = $statusCode;
    $this->data = $data;
    $this->message = $message;
    $this->status = $status;
  }
  public function response_json() {
    http_response_code($this->statusCode);
    echo json_encode([
      'statusCode' => $this->statusCode,
      'data' => $this->data,
      'message' => $this->message
    ]);
    die();
  }
  public function response_html() {
  }
}
