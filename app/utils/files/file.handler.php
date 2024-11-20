<?php

namespace App\Utils;

use App\Config\Accesos;
use Exception;

/**
 * Retorna el nombre de condominio en formato de directorio
 * @param string $condominio_name
 * @return string
 */
function directory($condominio_name) {
  $name = strtolower($condominio_name);
  $name = str_replace(' ', '_', $name);
  return $name;
}
/**
 * Devuelve el directorio publico donde se guarda un archivo, util para guardar archivos 
 * > DEBE USARSE DESDE APP
 * @param string $pin pin del condominio
 * @param string $prefix subruta dentro del directorio donde se guardara el archivo ('photos', 'vouchers', etc);
 * @return string
 */
function directorio_publico_condominio($pin, $prefix) {
  $condominio = Accesos::getCondominio($pin);
  $name = directory($condominio['name']);
  $urlfile = __DIR__ . "\\..\\..\\..\\public\\$name\\$prefix";
  if (!is_dir($urlfile)) {
    if (!mkdir($urlfile, 0755, true)) {
      throw new Exception('No se pudo crear el directorio: ' . $urlfile);
    }
  }
  return $urlfile;
}

/**
 * Unicamente visible desde el frontend (UN NIVEL ARRIBA) '../public/'
 * @param mixed $pin
 * @param mixed $prefix
 * @return string
 */
function url_public_condominio($pin, $prefix) {
  $condominio = Accesos::getCondominio($pin);
  $name = strtolower($condominio['name']);
  $name = str_replace(' ', '_', $name);
  $urlfile = "../public/$name/$prefix/";
  return $urlfile;
}
