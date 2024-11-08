<?php

namespace App\Utils;

use App\Config\Accesos;

/**
 * Devuelve el directorio publico donde se guarda un archivo 
 * @param string $pin pin del condominio
 * @param string $prefix subruta dentro del directorio donde se guardara el archivo
 * @return string
 */
function directorio_publico_condominio($pin, $prefix) {
  $condominio = Accesos::getCondominio($pin);
  $name = strtolower($condominio['name']);
  $name = str_replace(' ', '_', $name);
  $urlfile = __DIR__ . "\\..\\..\\public\\$name\\$prefix\\";
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
