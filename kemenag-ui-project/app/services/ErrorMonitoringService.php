<?php

namespace App\Services;

use Exception;

/**
 * Error Monitoring Service
 * Integrates with Sentry and custom error tracking
 */
class ErrorMonitoringService
{
    private $sentryDsn;
    private $environment;
    private $enabled;
    private $localLogPath;
    
    public function __construct()
    {
        $this->sentryDsn = env('SENTRY_DSN');
        $this->environment = env('APP_ENV', 'production');
        $this->enabled = env('ERROR_MONITORING_ENABLED', true);
        $this->localLogPath = __DIR__ . '/../../storage/logs/';
        
        // Initialize Sentry if available
        if ($this->enabled && $this->sentryDsn && class_exists('\Sentry\init')) {
            \Sentry\init([
                'dsn' => $this->sentryDsn,
                'environment' => $this->environment,
                'traces_sample_rate' => 1.0,
            ]);
        }
    }
    
    /**
     * Capture exception
     */
    public function captureException(Exception $exception, array $context = [])
    {
        // Log to local file
        $this->logToFile($exception, $context);
        
        // Send to Sentry if configured
        if ($this->enabled && function_exists('\Sentry\captureException')) {
            \Sentry\captureException($exception);
        }
        
        // Send alert email for critical errors
        if ($this->isCritical($exception)) {
            $this->sendAlertEmail($exception, $context);
        }
    }
    
    /**
     * Capture message
     */
    public function captureMessage(string $message, string $level = 'info', array $context = [])
    {
        $logData = [
            'message' => $message,
            'level' => $level,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'user_id' => $_SESSION['user_id'] ?? null,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        // Log to file
        $this->writeLog('messages', $logData);
        
        // Send to Sentry
        if ($this->enabled && function_exists('\Sentry\captureMessage')) {
            \Sentry\captureMessage($message, $level);
        }
    }
    
    /**
     * Track performance
     */
    public function trackPerformance(string $operation, float $duration, array $data = [])
    {
        $perfData = [
            'operation' => $operation,
            'duration' => $duration,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'memory' => memory_get_peak_usage(true) / 1024 / 1024 . ' MB'
        ];
        
        $this->writeLog('performance', $perfData);
        
        // Alert if slow
        if ($duration > 3.0) { // 3 seconds
            $this->captureMessage("Slow operation: {$operation} took {$duration}s", 'warning', $perfData);
        }
    }
    
    /**
     * Track user action
     */
    public function trackUserAction(string $action, array $data = [])
    {
        $actionData = [
            'action' => $action,
            'user_id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? 'guest',
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $this->writeLog('user_actions', $actionData);
    }
    
    /**
     * Log database query
     */
    public function logQuery(string $query, float $executionTime, array $bindings = [])
    {
        if ($executionTime > 1.0) { // Slow query > 1 second
            $queryData = [
                'query' => $query,
                'execution_time' => $executionTime,
                'bindings' => $bindings,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $this->writeLog('slow_queries', $queryData);
            $this->captureMessage("Slow query detected: {$executionTime}s", 'warning', $queryData);
        }
    }
    
    /**
     * Check if error is critical
     */
    private function isCritical(Exception $exception): bool
    {
        $criticalErrors = [
            'PDOException',
            'Error',
            'ParseError',
            'TypeError'
        ];
        
        foreach ($criticalErrors as $errorClass) {
            if ($exception instanceof $errorClass) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Log to file
     */
    private function logToFile(Exception $exception, array $context = [])
    {
        $logData = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'user_id' => $_SESSION['user_id'] ?? null
        ];
        
        $this->writeLog('errors', $logData);
    }
    
    /**
     * Write log to file
     */
    private function writeLog(string $type, array $data)
    {
        if (!is_dir($this->localLogPath)) {
            mkdir($this->localLogPath, 0777, true);
        }
        
        $filename = $this->localLogPath . $type . '_' . date('Y-m-d') . '.log';
        $logLine = json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        
        file_put_contents($filename, $logLine, FILE_APPEND);
    }
    
    /**
     * Send alert email
     */
    private function sendAlertEmail(Exception $exception, array $context = [])
    {
        $subject = "[CRITICAL ERROR] {$exception->getMessage()}";
        $body = "Critical error occurred:\n\n";
        $body .= "Type: " . get_class($exception) . "\n";
        $body .= "Message: {$exception->getMessage()}\n";
        $body .= "File: {$exception->getFile()}:{$exception->getLine()}\n";
        $body .= "Time: " . date('Y-m-d H:i:s') . "\n";
        $body .= "URL: " . ($_SERVER['REQUEST_URI'] ?? 'CLI') . "\n";
        $body .= "User: " . ($_SESSION['username'] ?? 'guest') . "\n\n";
        $body .= "Stack Trace:\n{$exception->getTraceAsString()}";
        
        // Send via EmailService
        try {
            $emailService = new EmailService();
            $emailService->sendEmail(
                env('ADMIN_EMAIL', 'admin@kemenag.go.id'),
                $subject,
                $body
            );
        } catch (Exception $e) {
            // Fallback: write to log if email fails
            error_log("Failed to send error alert email: " . $e->getMessage());
        }
    }
    
    /**
     * Get error statistics
     */
    public function getErrorStats(int $days = 7): array
    {
        $stats = [
            'total_errors' => 0,
            'by_type' => [],
            'by_day' => [],
            'critical_count' => 0
        ];
        
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $filename = $this->localLogPath . "errors_{$date}.log";
            
            if (file_exists($filename)) {
                $lines = file($filename, FILE_IGNORE_NEW_LINES);
                $dayCount = count($lines);
                
                $stats['total_errors'] += $dayCount;
                $stats['by_day'][$date] = $dayCount;
                
                // Count by type
                foreach ($lines as $line) {
                    $error = json_decode($line, true);
                    if (isset($error['type'])) {
                        if (!isset($stats['by_type'][$error['type']])) {
                            $stats['by_type'][$error['type']] = 0;
                        }
                        $stats['by_type'][$error['type']]++;
                    }
                }
            }
        }
        
        return $stats;
    }
}
