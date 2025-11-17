<?php
/**
 * Daily Cron Job
 * Run: 0 2 * * * php /path/to/cron/daily.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use App\Services\BackupService;
use App\Services\ErrorMonitoringService;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🌙 Daily Cron Job Started\n";
echo date('Y-m-d H:i:s') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// 1. Automated Backup
echo "📦 Starting automated backup...\n";
try {
    $backupService = new BackupService();
    $result = $backupService->performFullBackup();
    echo "✅ Backup completed: {$result['database']['filename']}\n";
} catch (Exception $e) {
    echo "❌ Backup failed: {$e->getMessage()}\n";
}

// 2. Clear old logs
echo "\n🗑️ Cleaning old logs...\n";
$logPath = __DIR__ . '/../storage/logs/';
$files = glob($logPath . '*_*.log');
$deleted = 0;
foreach ($files as $file) {
    if (filemtime($file) < strtotime('-30 days')) {
        unlink($file);
        $deleted++;
    }
}
echo "✅ Deleted {$deleted} old log files\n";

// 3. Error monitoring report
echo "\n📊 Generating error report...\n";
$monitoring = new ErrorMonitoringService();
$stats = $monitoring->getErrorStats(1);
echo "✅ Total errors today: {$stats['total_errors']}\n";

// 4. Database optimization
echo "\n⚙️ Optimizing database...\n";
try {
    $db = (new Core\Database(require __DIR__ . '/../config/database.php'))->getConnection();
    $tables = ['users', 'certificate_applications', 'audit_logs', 'forum_posts'];
    foreach ($tables as $table) {
        $db->exec("OPTIMIZE TABLE {$table}");
    }
    echo "✅ Database optimized\n";
} catch (Exception $e) {
    echo "❌ Optimization failed: {$e->getMessage()}\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Daily Cron Job Completed\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
