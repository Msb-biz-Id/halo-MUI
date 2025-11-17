<?php

namespace App\Middleware;

use App\Services\TurnstileService;

/**
 * Turnstile Middleware
 * Verify Cloudflare Turnstile token on protected routes
 */
class TurnstileMiddleware
{
    /**
     * Verify Turnstile token from POST request
     * 
     * @return bool True if verification passed, false otherwise
     */
    public static function verify(): bool
    {
        $turnstile = new TurnstileService();
        
        // If Turnstile is disabled, always pass
        if (!$turnstile->isEnabled()) {
            return true;
        }
        
        // Get token from POST data
        $token = $_POST['cf-turnstile-response'] ?? '';
        
        // Get client IP
        $ip = self::getClientIP();
        
        // Verify token
        $result = $turnstile->verify($token, $ip);
        
        // If verification failed, set error message
        if (!$result['success']) {
            $_SESSION['turnstile_error'] = $result['error'];
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if Turnstile verification passed
     * Redirect back with error if failed
     * 
     * @param string $redirectUrl URL to redirect to if verification fails
     */
    public static function check(string $redirectUrl = null)
    {
        if (!self::verify()) {
            if ($redirectUrl) {
                $_SESSION['error'] = $_SESSION['turnstile_error'] ?? 'Security verification failed';
                unset($_SESSION['turnstile_error']);
                header("Location: {$redirectUrl}");
                exit;
            }
        }
    }
    
    /**
     * Get client IP address
     */
    private static function getClientIP(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                
                // If multiple IPs (X-Forwarded-For), get first one
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * Render Turnstile widget for forms
     * 
     * @return string HTML for Turnstile widget
     */
    public static function widget(): string
    {
        $turnstile = new TurnstileService();
        
        if (!$turnstile->isEnabled()) {
            return '<!-- Turnstile disabled -->';
        }
        
        $siteKey = $turnstile->getSiteKey();
        
        if (empty($siteKey)) {
            return '<!-- Turnstile: Site key not configured -->';
        }
        
        $html = <<<HTML
<div class="cf-turnstile" 
     data-sitekey="{$siteKey}" 
     data-theme="light"
     data-size="normal"></div>
HTML;
        
        return $html;
    }
    
    /**
     * Get Turnstile script tag
     */
    public static function script(): string
    {
        $turnstile = new TurnstileService();
        
        if (!$turnstile->isEnabled()) {
            return '';
        }
        
        return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
}
