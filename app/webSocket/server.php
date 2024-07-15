<?php
// composer require cboden/ratchet
namespace App\WebSocket;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\HandleSocket;

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new HandleSocket()
    )
  ),
  8080
);
$server->run();
