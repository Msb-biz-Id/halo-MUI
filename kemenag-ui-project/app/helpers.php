<?php

/**
 * Helper Functions
 * Global helper functions used throughout the application
 */

/**
 * Generate URL
 * 
 * @param string $path
 * @return string
 */
function url($path = '')
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Generate asset URL
 * 
 * @param string $path
 * @return string
 */
function asset($path)
{
    return APP_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Redirect to URL
 * 
 * @param string $url
 * @return void
 */
function redirect($url)
{
    header("Location: " . url($url));
    exit();
}

/**
 * Get old input value
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Flash old input
 * 
 * @return void
 */
function flashOldInput()
{
    $_SESSION['old'] = $_POST;
}

/**
 * Get flash message
 * 
 * @param string $key
 * @return string|null
 */
function flash($key)
{
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Set flash message
 * 
 * @param string $key
 * @param string $message
 * @return void
 */
function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

/**
 * Escape HTML
 * 
 * @param string $string
 * @return string
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Dump and die
 * 
 * @param mixed $data
 * @return void
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Dump data
 * 
 * @param mixed $data
 * @return void
 */
function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

/**
 * Check if user is authenticated
 * 
 * @return bool
 */
function auth()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get authenticated user data
 * 
 * @param string|null $key
 * @return mixed
 */
function user($key = null)
{
    if ($key) {
        return $_SESSION[$key] ?? null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role_name' => $_SESSION['role_name'] ?? null,
    ];
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function csrf_token()
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Generate CSRF field
 * 
 * @return string
 */
function csrf_field()
{
    $token = csrf_token();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
}

/**
 * Verify CSRF token
 * 
 * @param string $token
 * @return bool
 */
function verify_csrf($token)
{
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

/**
 * Sanitize string
 * 
 * @param string $string
 * @return string
 */
function sanitize($string)
{
    return filter_var($string, FILTER_SANITIZE_STRING);
}

/**
 * Validate email
 * 
 * @param string $email
 * @return bool
 */
function is_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL
 * 
 * @param string $url
 * @return bool
 */
function is_url($url)
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Format date
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = 'd M Y')
{
    return date($format, strtotime($date));
}

/**
 * Format datetime
 * 
 * @param string $datetime
 * @param string $format
 * @return string
 */
function format_datetime($datetime, $format = 'd M Y H:i')
{
    return date($format, strtotime($datetime));
}

/**
 * Time ago
 * 
 * @param string $datetime
 * @return string
 */
function time_ago($datetime)
{
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return $difference . ' detik yang lalu';
    } elseif ($difference < 3600) {
        return floor($difference / 60) . ' menit yang lalu';
    } elseif ($difference < 86400) {
        return floor($difference / 3600) . ' jam yang lalu';
    } elseif ($difference < 604800) {
        return floor($difference / 86400) . ' hari yang lalu';
    } else {
        return format_date($datetime);
    }
}

/**
 * Truncate string
 * 
 * @param string $string
 * @param int $length
 * @param string $append
 * @return string
 */
function str_limit($string, $length = 100, $append = '...')
{
    if (mb_strlen($string) <= $length) {
        return $string;
    }
    
    return mb_substr($string, 0, $length) . $append;
}

/**
 * Generate slug
 * 
 * @param string $string
 * @return string
 */
function slug($string)
{
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Upload file
 * 
 * @param array $file
 * @param string $destination
 * @param array $allowedExtensions
 * @return string|false Filename on success, false on failure
 */
function upload_file($file, $destination = 'uploads/', $allowedExtensions = null)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = $allowedExtensions ?? explode(',', ALLOWED_EXTENSIONS);
    
    if (!in_array($extension, $allowed)) {
        return false;
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = PUBLIC_PATH . '/' . $destination . $filename;
    
    // Create directory if not exists
    $dir = dirname($filepath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return false;
}

/**
 * Delete file
 * 
 * @param string $filepath
 * @return bool
 */
function delete_file($filepath)
{
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Format bytes to human readable
 * 
 * @param int $bytes
 * @param int $precision
 * @return string
 */
function format_bytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Generate random string
 * 
 * @param int $length
 * @return string
 */
function random_string($length = 32)
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Encrypt string
 * 
 * @param string $string
 * @return string
 */
function encrypt($string)
{
    $key = ENCRYPTION_KEY;
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * Decrypt string
 * 
 * @param string $string
 * @return string|false
 */
function decrypt($string)
{
    $key = ENCRYPTION_KEY;
    list($encrypted, $iv) = explode('::', base64_decode($string), 2);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}

/**
 * Log audit
 * 
 * @param int $userId
 * @param string $action
 * @param string $tableName
 * @param int $recordId
 * @return void
 */
function log_audit($userId, $action, $tableName, $recordId)
{
    $db = \Core\Database::getInstance();
    $sql = "INSERT INTO audit_logs (user_id, action, table_name, record_id, timestamp) 
            VALUES (:user_id, :action, :table_name, :record_id, NOW())";
    
    $db->query($sql)
       ->bind(':user_id', $userId)
       ->bind(':action', $action)
       ->bind(':table_name', $tableName)
       ->bind(':record_id', $recordId)
       ->execute();
}

/**
 * Check rate limit
 * 
 * @param string $key
 * @param int $maxAttempts
 * @param int $decayMinutes
 * @return bool
 */
function check_rate_limit($key, $maxAttempts = null, $decayMinutes = null)
{
    if (!RATE_LIMIT_ENABLED) {
        return true;
    }
    
    $maxAttempts = $maxAttempts ?? RATE_LIMIT_MAX_ATTEMPTS;
    $decayMinutes = $decayMinutes ?? RATE_LIMIT_DECAY_MINUTES;
    
    $cacheKey = 'rate_limit_' . $key;
    $cacheFile = CACHE_PATH . '/' . md5($cacheKey) . '.cache';
    
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        
        // Check if decay time has passed
        if (time() - $data['time'] > ($decayMinutes * 60)) {
            unlink($cacheFile);
            return true;
        }
        
        // Check attempts
        if ($data['attempts'] >= $maxAttempts) {
            return false;
        }
        
        // Increment attempts
        $data['attempts']++;
        file_put_contents($cacheFile, json_encode($data));
    } else {
        // First attempt
        $data = ['attempts' => 1, 'time' => time()];
        file_put_contents($cacheFile, json_encode($data));
    }
    
    return true;
}

/**
 * Clear rate limit
 * 
 * @param string $key
 * @return void
 */
function clear_rate_limit($key)
{
    $cacheKey = 'rate_limit_' . $key;
    $cacheFile = CACHE_PATH . '/' . md5($cacheKey) . '.cache';
    
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
}
