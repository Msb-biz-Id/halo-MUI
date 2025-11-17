<?php
/**
 * WebSocket Server Runner
 * Start real-time WebSocket server
 * 
 * Usage: php websocket_server.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Services\WebSocketService;

$port = env('WEBSOCKET_PORT', 8080);
$host = env('WEBSOCKET_HOST', '0.0.0.0');

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🚀 WebSocket Server Starting...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Host: {$host}\n";
echo "Port: {$port}\n";
echo "Environment: " . env('APP_ENV', 'production') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketService()
        )
    ),
    $port,
    $host
);

echo "✅ WebSocket server running on ws://{$host}:{$port}\n";
echo "📡 Listening for connections...\n\n";
echo "Press Ctrl+C to stop\n\n";

$server->run();
