<?php

namespace App\Middleware;

/**
 * Maintenance Mode Middleware
 * Check if site is in maintenance mode
 */
class MaintenanceMiddleware
{
    public static function check()
    {
        $maintenanceFile = __DIR__ . '/../../storage/maintenance.json';
        
        if (!file_exists($maintenanceFile)) {
            return; // Not in maintenance mode
        }
        
        $settings = json_decode(file_get_contents($maintenanceFile), true);
        
        if (!$settings['enabled']) {
            return;
        }
        
        // Allow admin users
        if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') {
            return;
        }
        
        // Check allowed IPs
        $clientIP = $_SERVER['REMOTE_ADDR'] ?? '';
        if (!empty($settings['allowed_ips']) && in_array($clientIP, $settings['allowed_ips'])) {
            return;
        }
        
        // Show maintenance page
        http_response_code(503);
        header('Retry-After: ' . ($settings['retry_after'] ?? 3600));
        
        extract($settings);
        require __DIR__ . '/../../public/maintenance.php';
        exit;
    }
}
