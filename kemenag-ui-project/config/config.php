<?php
/**
 * Main Configuration File
 * Loads environment variables and defines constants
 */

// Load environment variables from .env file
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Helper function to get environment variables
if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        
        // Convert boolean strings
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        
        return $value;
    }
}

// Application Configuration
define('APP_NAME', env('APP_NAME', 'Kemenag UI'));
define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', env('APP_DEBUG', false));
define('APP_URL', rtrim(env('APP_URL', 'http://localhost'), '/'));
define('APP_TIMEZONE', env('APP_TIMEZONE', 'Asia/Jakarta'));

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/' . env('UPLOAD_PATH', 'uploads/'));
define('LOG_PATH', STORAGE_PATH . '/logs');
define('CACHE_PATH', STORAGE_PATH . '/cache');
define('VIEW_PATH', APP_PATH . '/views');

// Database Configuration
define('DB_CONNECTION', env('DB_CONNECTION', 'mysql'));
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_DATABASE', env('DB_DATABASE', 'kemenag_ui_db'));
define('DB_USERNAME', env('DB_USERNAME', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

// Email Configuration
define('MAIL_DRIVER', env('MAIL_DRIVER', 'smtp'));
define('MAIL_HOST', env('MAIL_HOST', 'smtp.gmail.com'));
define('MAIL_PORT', env('MAIL_PORT', '587'));
define('MAIL_USERNAME', env('MAIL_USERNAME', ''));
define('MAIL_PASSWORD', env('MAIL_PASSWORD', ''));
define('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls'));
define('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'noreply@kemenag.go.id'));
define('MAIL_FROM_NAME', env('MAIL_FROM_NAME', APP_NAME));

// Google Gemini AI Configuration
define('GEMINI_API_KEY', env('GEMINI_API_KEY', ''));
define('GEMINI_MODEL', env('GEMINI_MODEL', 'gemini-pro'));

// WhatsApp Configuration
define('WHATSAPP_PROVIDER', env('WHATSAPP_PROVIDER', 'waha'));
define('WAHA_API_URL', env('WAHA_API_URL', 'http://localhost:3000'));
define('WAHA_API_KEY', env('WAHA_API_KEY', ''));
define('WAHA_SESSION_NAME', env('WAHA_SESSION_NAME', 'default'));
define('FONNTE_API_URL', env('FONNTE_API_URL', 'https://api.fonnte.com'));
define('FONNTE_TOKEN', env('FONNTE_TOKEN', ''));

// Session Configuration
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 120) * 60); // Convert to seconds
define('SESSION_DRIVER', env('SESSION_DRIVER', 'file'));

// Security Configuration
define('APP_KEY', env('APP_KEY', ''));
define('CSRF_TOKEN_NAME', env('CSRF_TOKEN_NAME', 'csrf_token'));
define('ENCRYPTION_KEY', env('ENCRYPTION_KEY', ''));

// File Upload Configuration
define('MAX_FILE_SIZE', env('MAX_FILE_SIZE', 20971520)); // 20MB in bytes
define('ALLOWED_EXTENSIONS', env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx'));

// Pagination Configuration
define('ITEMS_PER_PAGE', env('ITEMS_PER_PAGE', 20));

// MFA Configuration
define('MFA_ISSUER', env('MFA_ISSUER', 'Kemenag UI'));
define('MFA_ENABLED', env('MFA_ENABLED', true));

// Cache Configuration
define('CACHE_DRIVER', env('CACHE_DRIVER', 'file'));
define('CACHE_LIFETIME', env('CACHE_LIFETIME', 3600));

// SEO Configuration
define('SEO_SITE_NAME', env('SEO_SITE_NAME', APP_NAME));
define('SEO_SITE_DESCRIPTION', env('SEO_SITE_DESCRIPTION', 'Platform informasi keagamaan'));
define('SEO_DEFAULT_IMAGE', env('SEO_DEFAULT_IMAGE', '/assets/images/og-image.jpg'));
define('SEO_TWITTER_HANDLE', env('SEO_TWITTER_HANDLE', '@kemenag_ri'));

// Rate Limiting Configuration
define('RATE_LIMIT_ENABLED', env('RATE_LIMIT_ENABLED', true));
define('RATE_LIMIT_MAX_ATTEMPTS', env('RATE_LIMIT_MAX_ATTEMPTS', 60));
define('RATE_LIMIT_DECAY_MINUTES', env('RATE_LIMIT_DECAY_MINUTES', 1));

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_PATH . '/error.log');
}

// Create necessary directories if they don't exist
$directories = [STORAGE_PATH, LOG_PATH, CACHE_PATH, UPLOAD_PATH];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

return [
    'app' => [
        'name' => APP_NAME,
        'env' => APP_ENV,
        'debug' => APP_DEBUG,
        'url' => APP_URL,
        'timezone' => APP_TIMEZONE,
    ],
    'database' => [
        'connection' => DB_CONNECTION,
        'host' => DB_HOST,
        'port' => DB_PORT,
        'database' => DB_DATABASE,
        'username' => DB_USERNAME,
        'password' => DB_PASSWORD,
        'charset' => DB_CHARSET,
    ],
    'mail' => [
        'driver' => MAIL_DRIVER,
        'host' => MAIL_HOST,
        'port' => MAIL_PORT,
        'username' => MAIL_USERNAME,
        'password' => MAIL_PASSWORD,
        'encryption' => MAIL_ENCRYPTION,
        'from' => [
            'address' => MAIL_FROM_ADDRESS,
            'name' => MAIL_FROM_NAME,
        ],
    ],
];
