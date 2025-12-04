<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/ChatServer.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Core\ChatServer;

$port = 8080;
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    $port
);

echo "WebSocket server started on port {$port}\n";
$server->run();
