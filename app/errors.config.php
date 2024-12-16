<?php

use const App\Config\SHOW_ERRORS;

use function App\Providers\logger;

ini_set('display_errors', SHOW_ERRORS ? 1 : 0);  // No mostrar errores en pantalla
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);  // Suprimir notices y warnings
set_error_handler(function ($errno, $errstr, $errfile, $errline) {

  switch ($errno) {
    case E_NOTICE:
    case E_USER_NOTICE:
    case E_WARNING:
    case E_USER_WARNING:
      logger()->notice("Notice: {$errstr} in {$errfile} on line {$errline}");
      break;
  }
  return true;
});
