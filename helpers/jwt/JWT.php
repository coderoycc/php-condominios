<?php

namespace Helpers\JWT;

require_once(__DIR__ . '/../secrets.keys.php');

use Auth\Config\KEY;

class JWT {
  public static function encode(array $payload) {
    try {
      $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
      ];
      $base64UrlHeader = base64_encode(json_encode($header));
      $base64UrlPayload = base64_encode(json_encode($payload));
      $base64UrlPayload = rtrim($base64UrlPayload, '=');
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, KEY::$private_key, true);
      $base64UrlSignature = base64_encode($signature);
      $base64UrlSignature = rtrim($base64UrlSignature, '=');
      $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
      return $token;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return null;
  }

  public static function decode($token) {
    try {
      $error = null;
      $partes = explode('.', $token);
      if (count($partes) === 3) {
        $base64UrlHeader = $partes[0];
        $base64UrlPayload = $partes[1];
        $signatureProvided = $partes[2];
        $payload = json_decode(base64_decode($base64UrlPayload), true);

        // Verificar la firma
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, KEY::$private_key, true);
        $base64UrlSignature = base64_encode($signature);
        $base64UrlSignature = rtrim($base64UrlSignature, '=');
        if ($base64UrlSignature === $signatureProvided) {
          if ($payload['exp'] >= time()) { // Verificar la expiración del token
            return $payload; // Token válido
          } else {
            $error = 'El token ha vencido'; // Token expirado
          }
        } else {
          $error = 'Firma no válida';
        }
      } else {
        $error = 'Token no válido ' . $token;
      }
    } catch (\Throwable $th) {
      $error = 'Error al decodificar el token';
    }
    return ['error' => $error];
  }
}
