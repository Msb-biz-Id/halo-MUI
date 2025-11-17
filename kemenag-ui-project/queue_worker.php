<?php
/**
 * Queue Worker Runner
 * Process background jobs
 * 
 * Usage: php queue_worker.php [queue_name]
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';

use App\Services\QueueService;

$queue = $argv[1] ?? 'default';

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔄 Queue Worker Starting...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Queue: {$queue}\n";
echo "Environment: " . env('APP_ENV', 'production') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$queueService = new QueueService();
$queueService->work($queue);
