<?php
/**
 * Hourly Cron Job
 * Run: 0 * * * * php /path/to/cron/hourly.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use App\Services\QueueService;

echo "⏰ Hourly Cron: " . date('Y-m-d H:i:s') . "\n";

// 1. Process pending notifications
echo "📧 Processing pending notifications...\n";
$queueService = new QueueService();
$stats = $queueService->getStats();
echo "Queue stats: {$stats['pending']} pending, {$stats['failed']} failed\n";

// 2. Update cache
echo "🔄 Updating cache...\n";
// TODO: Clear/refresh cache

// 3. Check system health
echo "❤️ System health check...\n";
$health = [
    'disk_usage' => disk_free_space('/') / disk_total_space('/') * 100,
    'memory_usage' => memory_get_usage(true) / 1024 / 1024,
];
echo sprintf("Disk: %.2f%% free, Memory: %.2f MB\n", $health['disk_usage'], $health['memory_usage']);

echo "✅ Hourly cron completed\n\n";
