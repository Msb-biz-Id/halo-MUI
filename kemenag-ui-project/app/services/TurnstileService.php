<?php

namespace App\Services;

/**
 * Cloudflare Turnstile Service
 * Anti-bot protection and brute-force prevention
 */
class TurnstileService
{
    private $secretKey;
    private $enabled;
    private $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    
    public function __construct()
    {
        $this->secretKey = env('TURNSTILE_SECRET_KEY');
        $this->enabled = env('TURNSTILE_ENABLED', true);
    }
    
    /**
     * Verify Turnstile token
     * 
     * @param string $token The Turnstile token from client
     * @param string|null $remoteIp Client IP address (optional)
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function verify(string $token, ?string $remoteIp = null): array
    {
        // If Turnstile is disabled, always return success
        if (!$this->enabled) {
            return ['success' => true, 'error' => null];
        }
        
        // If no secret key configured, return error
        if (empty($this->secretKey)) {
            error_log('Turnstile: Secret key not configured');
            return ['success' => false, 'error' => 'Turnstile not configured'];
        }
        
        // If no token provided, return error
        if (empty($token)) {
            return ['success' => false, 'error' => 'Turnstile token missing'];
        }
        
        // Prepare verification data
        $data = [
            'secret' => $this->secretKey,
            'response' => $token
        ];
        
        // Add IP if provided
        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }
        
        // Make API request to Cloudflare
        $ch = curl_init($this->verifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Check for curl errors
        if ($curlError) {
            error_log("Turnstile: cURL error - {$curlError}");
            return ['success' => false, 'error' => 'Verification request failed'];
        }
        
        // Check HTTP status
        if ($httpCode !== 200) {
            error_log("Turnstile: HTTP error {$httpCode}");
            return ['success' => false, 'error' => 'Verification service unavailable'];
        }
        
        // Parse response
        $result = json_decode($response, true);
        
        if (!$result) {
            error_log('Turnstile: Invalid JSON response');
            return ['success' => false, 'error' => 'Invalid verification response'];
        }
        
        // Log verification result for monitoring
        $this->logVerification($result, $remoteIp);
        
        // Return result
        if ($result['success']) {
            return ['success' => true, 'error' => null];
        } else {
            $errorCodes = $result['error-codes'] ?? ['unknown-error'];
            $errorMessage = $this->getErrorMessage($errorCodes);
            return ['success' => false, 'error' => $errorMessage];
        }
    }
    
    /**
     * Get user-friendly error message
     */
    private function getErrorMessage(array $errorCodes): string
    {
        $messages = [
            'missing-input-secret' => 'Configuration error',
            'invalid-input-secret' => 'Configuration error',
            'missing-input-response' => 'Please complete the verification',
            'invalid-input-response' => 'Verification failed, please try again',
            'bad-request' => 'Verification request error',
            'timeout-or-duplicate' => 'Verification expired, please try again',
            'internal-error' => 'Verification service error',
        ];
        
        foreach ($errorCodes as $code) {
            if (isset($messages[$code])) {
                return $messages[$code];
            }
        }
        
        return 'Verification failed, please try again';
    }
    
    /**
     * Log verification attempt
     */
    private function logVerification(array $result, ?string $ip)
    {
        $logData = [
            'success' => $result['success'],
            'challenge_ts' => $result['challenge_ts'] ?? null,
            'hostname' => $result['hostname'] ?? null,
            'error_codes' => $result['error-codes'] ?? [],
            'ip' => $ip,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log to file
        $logFile = __DIR__ . '/../../storage/logs/turnstile_' . date('Y-m-d') . '.log';
        $logLine = json_encode($logData) . PHP_EOL;
        file_put_contents($logFile, $logLine, FILE_APPEND);
        
        // If failed verification, log to main error log
        if (!$result['success']) {
            error_log("Turnstile verification failed: IP={$ip}, errors=" . implode(',', $result['error-codes'] ?? []));
        }
    }
    
    /**
     * Check if Turnstile is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->secretKey);
    }
    
    /**
     * Get site key for frontend
     */
    public function getSiteKey(): string
    {
        return env('TURNSTILE_SITE_KEY', '');
    }
    
    /**
     * Get verification statistics
     */
    public function getStats(int $days = 7): array
    {
        $stats = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'by_error' => [],
            'by_day' => []
        ];
        
        $logPath = __DIR__ . '/../../storage/logs/';
        
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $file = $logPath . "turnstile_{$date}.log";
            
            if (!file_exists($file)) {
                continue;
            }
            
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            $daySuccess = 0;
            $dayFailed = 0;
            
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if (!$data) continue;
                
                $stats['total']++;
                
                if ($data['success']) {
                    $stats['success']++;
                    $daySuccess++;
                } else {
                    $stats['failed']++;
                    $dayFailed++;
                    
                    // Count by error type
                    foreach ($data['error_codes'] as $code) {
                        if (!isset($stats['by_error'][$code])) {
                            $stats['by_error'][$code] = 0;
                        }
                        $stats['by_error'][$code]++;
                    }
                }
            }
            
            $stats['by_day'][$date] = [
                'success' => $daySuccess,
                'failed' => $dayFailed,
                'total' => $daySuccess + $dayFailed
            ];
        }
        
        return $stats;
    }
    
    /**
     * Get suspicious IPs (multiple failed verifications)
     */
    public function getSuspiciousIPs(int $threshold = 5, int $hours = 24): array
    {
        $suspicious = [];
        $cutoffTime = time() - ($hours * 3600);
        
        $logPath = __DIR__ . '/../../storage/logs/';
        $files = glob($logPath . 'turnstile_*.log');
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                continue;
            }
            
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if (!$data || $data['success'] || empty($data['ip'])) {
                    continue;
                }
                
                $ip = $data['ip'];
                
                if (!isset($suspicious[$ip])) {
                    $suspicious[$ip] = [
                        'ip' => $ip,
                        'failed_count' => 0,
                        'first_seen' => $data['timestamp'],
                        'last_seen' => $data['timestamp']
                    ];
                }
                
                $suspicious[$ip]['failed_count']++;
                $suspicious[$ip]['last_seen'] = $data['timestamp'];
            }
        }
        
        // Filter by threshold
        $suspicious = array_filter($suspicious, function($data) use ($threshold) {
            return $data['failed_count'] >= $threshold;
        });
        
        // Sort by failed count (descending)
        usort($suspicious, function($a, $b) {
            return $b['failed_count'] - $a['failed_count'];
        });
        
        return $suspicious;
    }
}
