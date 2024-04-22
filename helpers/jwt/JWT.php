<?php

namespace Helpers\JWT;

require_once(__DIR__ . '/../config/secrets.keys.php');

use Auth\Config\KEY;

class JWT {
  public static function encode($payload) {
    try {
      $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
      ];

      // Payload del token
      // $payload = [
      //   'usuario_id' => $usuario_id,
      //   'exp' => time() + (60 * 60) // El token expirará en 1 hora
      // ];

      // Codificación del header y payload en base64
      $base64UrlHeader = base64_encode(json_encode($header));
      $base64UrlPayload = base64_encode(json_encode($payload));

      // Generación de la firma usando HMAC y SHA-256
      $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, KEY::$private_key, true);

      // Codificación de la firma en base64
      $base64UrlSignature = base64_encode($signature);

      // Generación del token final
      $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

      return $token;
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }

  public static function decode($token) {
    try {
      $partes = explode('.', $token);

      // Verificar que haya tres partes
      if (count($partes) === 3) {
        $base64UrlHeader = $partes[0];
        $base64UrlPayload = $partes[1];
        $signatureProvided = $partes[2];

        // Decodificar el header y payload
        $header = json_decode(base64_decode($base64UrlHeader), true);
        $payload = json_decode(base64_decode($base64UrlPayload), true);

        // Verificar la firma
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, KEY::$private_key, true);
        $base64UrlSignature = base64_encode($signature);

        // Comparar la firma proporcionada con la firma generada
        if ($base64UrlSignature === $signatureProvided) {
          // Verificar la expiración del token
          if ($payload['exp'] >= time()) {
            return $payload; // Token válido
          } else {
            return 'El token ha vencido'; // Token expirado
          }
        } else {
          return false; // Firma no válida
        }
      } else {
        return false; // Token mal formado
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }
}
