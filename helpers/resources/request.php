<?php

namespace Helpers\Resources;

class Request {
  /**
   * Devuelve un booleano indicando si los campos requeridos $keys estan en el $req. TRUE si estan todos los campos y false si falta uno o mas campos
   * @param array $keys
   * @param array $req
   * @return bool
   */
  public static function required(array $keys, array $req): bool {
    $bool = true;
    foreach ($keys as $key) {
      if (!array_key_exists($key, $req)) {
        $bool = false;
        break;
      }
    }
    return $bool;
  }
}
