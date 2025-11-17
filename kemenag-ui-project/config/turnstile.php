<?php

/**
 * Cloudflare Turnstile Configuration
 * 
 * Turnstile is Cloudflare's smart CAPTCHA alternative
 * Protects your site from bots, spam, and brute-force attacks
 * 
 * @see https://developers.cloudflare.com/turnstile/
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Turnstile Enabled
    |--------------------------------------------------------------------------
    |
    | Set to true to enable Turnstile verification on protected forms
    | Set to false to disable (useful for local development)
    |
    */
    'enabled' => env('TURNSTILE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Site Key (Public)
    |--------------------------------------------------------------------------
    |
    | The public site key for Turnstile widget
    | This is safe to be visible in client-side code
    |
    | Get from: https://dash.cloudflare.com/ → Turnstile → Your Site
    |
    */
    'site_key' => env('TURNSTILE_SITE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Secret Key (Private)
    |--------------------------------------------------------------------------
    |
    | The secret key for server-side verification
    | NEVER expose this key in client-side code!
    |
    | Get from: https://dash.cloudflare.com/ → Turnstile → Your Site
    |
    */
    'secret_key' => env('TURNSTILE_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Verify URL
    |--------------------------------------------------------------------------
    |
    | The Cloudflare Turnstile verification endpoint
    | DO NOT change unless Cloudflare updates their API
    |
    */
    'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',

    /*
    |--------------------------------------------------------------------------
    | Widget Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for Turnstile widget
    |
    | theme: light, dark, auto
    | size: normal, compact, flexible
    | language: auto, en, id, ar, etc.
    |
    */
    'widget' => [
        'theme' => env('TURNSTILE_THEME', 'light'),
        'size' => env('TURNSTILE_SIZE', 'normal'),
        'language' => env('TURNSTILE_LANGUAGE', 'auto'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Protected Routes
    |--------------------------------------------------------------------------
    |
    | Routes that require Turnstile verification
    | These are automatically protected by the middleware
    |
    */
    'protected_routes' => [
        // Authentication
        'auth/login',
        'auth/register',
        'auth/forgot-password',
        'auth/reset-password',
        
        // User Forms
        'certificate/apply',
        'forum/create-topic',
        'forum/topic/*/reply',
        'contact',
        
        // Admin (optional)
        // 'admin/login',
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist
    |--------------------------------------------------------------------------
    |
    | IPs that bypass Turnstile verification
    | Useful for internal testing, monitoring tools, etc.
    |
    */
    'ip_whitelist' => [
        // '127.0.0.1',
        // '::1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable/disable logging of Turnstile verifications
    | Logs stored in: storage/logs/turnstile_YYYY-MM-DD.log
    |
    */
    'logging' => [
        'enabled' => env('TURNSTILE_LOGGING', true),
        'log_success' => env('TURNSTILE_LOG_SUCCESS', true),
        'log_failure' => env('TURNSTILE_LOG_FAILURE', true),
        'log_path' => BASE_PATH . '/storage/logs/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious IP Detection
    |--------------------------------------------------------------------------
    |
    | Automatic detection of suspicious IPs based on failed attempts
    |
    | threshold: Minimum failed attempts to flag as suspicious
    | time_window: Time window in hours to count failures
    |
    */
    'suspicious_detection' => [
        'enabled' => true,
        'threshold' => env('TURNSTILE_SUSPICIOUS_THRESHOLD', 5),
        'time_window' => env('TURNSTILE_SUSPICIOUS_WINDOW', 24), // hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Custom error messages for different scenarios
    | Supports multi-language
    |
    */
    'error_messages' => [
        'en' => [
            'missing_token' => 'Security verification failed. Please try again.',
            'invalid_token' => 'Invalid security token. Please refresh and try again.',
            'expired_token' => 'Security token expired. Please try again.',
            'network_error' => 'Could not verify security. Please check your connection.',
            'disabled' => 'Security verification is temporarily disabled.',
        ],
        'id' => [
            'missing_token' => 'Verifikasi keamanan gagal. Silakan coba lagi.',
            'invalid_token' => 'Token keamanan tidak valid. Silakan refresh dan coba lagi.',
            'expired_token' => 'Token keamanan kadaluarsa. Silakan coba lagi.',
            'network_error' => 'Tidak dapat memverifikasi keamanan. Periksa koneksi Anda.',
            'disabled' => 'Verifikasi keamanan sementara dinonaktifkan.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bypass Mode (Development)
    |--------------------------------------------------------------------------
    |
    | Force bypass Turnstile verification
    | ONLY use in development/testing environments!
    |
    | WARNING: NEVER enable in production!
    |
    */
    'bypass_mode' => env('TURNSTILE_BYPASS', false),

    /*
    |--------------------------------------------------------------------------
    | Testing Mode
    |--------------------------------------------------------------------------
    |
    | Use Cloudflare's test keys for development
    | 
    | Site Key (always passes): 1x00000000000000000000AA
    | Site Key (always fails): 2x00000000000000000000AB
    | Site Key (token already spent): 3x00000000000000000000FF
    |
    */
    'testing' => [
        'enabled' => env('TURNSTILE_TESTING', false),
        'site_key' => '1x00000000000000000000AA',
        'secret_key' => '1x0000000000000000000000000000000AA',
    ],
];
