<?php

//$fecha = 
$version = "5";
$id = 5;

$data = array(
  'id_tarea' => $id,
  'nombre' => 'RIEGO',
  'descripcion' => 'Riego del huerto cada 12 dias',
  'fecha' => '2022-12-16 17:20:00 GTM-4'
);

$body = array(
  'app_id' => '6efa7be7-4b09-4b58-a9b6-ff656938238d',
  //Segmentos de usuarios a los cuales va dirigido el mensaje
  //'included_segments' => ["Active Subscriptions","Inactive Subscriptions"],
  //'include_external_user_ids' => ["3"],
  'include_player_ids' => ["14aba094-ec24-4312-bc39-3f8b5ffe2da8"],
  //Contenido del mensaje de la notificacion push
  'contents' => array('en' => 'HOLA',/**/ 'es' => 'HOLA'),
  //Define el titulo de la notificacion push
  'headings' => array('en' => 'hollllaaaa', 'es' => 'holaaaa'),
  'name' => 'ACLOAPP',
  'priority' => 10,
  'data' => $data,
  // Programacion del envio del mensaje
  //'send_after' => '2022-12-03 16:10:00 GMT-4',
);

//echo json_encode($body);

/*$response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
  'body' => json_encode($body),
  'headers' => [
    'Authorization' => 'Basic NjA0MWFjY2UtZmExNC00MmFhLWIyM2QtYjRlZjk0NWFiY2Ix',
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);*/

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json; charset=utf-8',
  'Authorization: Basic ZDhmOGEwZjAtYzgwNy00MDkyLWJlYmQtYWJlNTViMDEzZGZl'
));
$jsonnn = json_encode($body);
var_dump($jsonnn);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonnn);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$response = curl_exec($ch);
curl_close($ch);

$response = json_decode($response);

print_r($response);

if (isset($response->errors)) {
  echo json_encode(array('status' => false, 'message' => $response->errors[0]));
} else {
  echo json_encode(array('status' => true, 'message' => 'Notificación registrada correctamente', 'data' => array('id' => $response->id,/* 'recipients' => $response->recipients,*/ 'external_id' => $response->external_id)));
}
