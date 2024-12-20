<?php

namespace App\Providers;

use Throwable;

use const App\Config\PRODUCTION;
use const App\Config\SHOW_ERRORS;

class LoggerProvider {
  private static string $base = __DIR__ . '/../logs/log_';
  private string $file = '';
  public function __construct() {
    $this->file();
  }
  public function error(Throwable $error) {
    if (SHOW_ERRORS) {
      var_dump($error);
    }
    $message = "[ERROR]\t\t" . date('Y-m-d H:i:s') . "\t- "  . $error->getMessage() . ' - ' . $error->getFile() . ' - ' . $error->getLine() . "\n";
    file_put_contents($this->file, $message, FILE_APPEND);
  }
  public function request($message) {
    $message = "[REQST]\t\t" . date('Y-m-d H:i:s') . "\t- " . $message . "\n";
    file_put_contents($this->file, $message, FILE_APPEND);
  }
  public function response($message) {
    if (!PRODUCTION) {
      $message = "[RESPN]\t\t" . date('Y-m-d H:i:s') . "\t- " . $message . "\n";
      file_put_contents($this->file, $message, FILE_APPEND);
    }
  }
  public function notice($output) {
    $message = "[NOTCE]\t\t" . date('Y-m-d H:i:s') . "\t- " . $output . "\n";
    file_put_contents($this->file, $message, FILE_APPEND);
  }
  public function debug($message) {
    if (!PRODUCTION) {
      $message = "[DEBUG]\t\t" . date('Y-m-d H:i:s') . "\t- " . $message . "\n";
      file_put_contents($this->file, $message, FILE_APPEND);
    }
  }
  /**
   * Verificar si existe el archivo log diario si no lo crea
   * @return void
   */
  private function file() {
    $this->file = self::$base . date('Y-m-d') . '.log';
    if (!file_exists($this->file)) {
      file_put_contents($this->file, '');
    }
  }
}

if (function_exists('logger') === false) {
  function logger() {
    return new LoggerProvider();
  }
}
