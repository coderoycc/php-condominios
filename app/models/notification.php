<?php

namespace App\Models;

class Notification {
  private static $url = 'https://onesignal.com/api/v1/notifications';
  private static $token = "ZDhmOGEwZjAtYzgwNy00MDkyLWJlYmQtYWJlNTViMDEzZGZl";
  private static $app_id = "6efa7be7-4b09-4b58-a9b6-ff656938238d";
  public static function send_id($uuid, $message, $header, $data_call = []) {
    $headers = array(
      'Authorization: Basic ' . self::$token,
      'Content-Type: application/json; charset=utf-8'
    );
    $data = array(
      "include_player_ids" => [$uuid],
      "contents" => array(
        "en" => $message,
        "es" => $message
      ),
      "headings" => array(
        "en" => $header,
        "es" => $header
      ),
      "app_id" => self::$app_id,
      'priority' => 10
    );
    if (!empty($data_call))
      $data['data'] =  $data_call;

    $response = null;
    $jsson_data = json_encode($data);
    try {
      $ch = curl_init(self::$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsson_data);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      $response = curl_exec($ch);
      $response = json_decode($response, true);
      curl_close($ch);
    } catch (\Throwable $th) {
      $response['errors'] = $th->getMessage();
    }
    return $response;
  }
  public static function send_segment() {
  }
  public static function send_all() {
  }
}
