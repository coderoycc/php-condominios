<?php

namespace Helpers\Resources;

class Request {
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
