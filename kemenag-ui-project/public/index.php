<?php

/**
 * Front Controller
 * Entry point for all requests
 */

// Error reporting (disable in production!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load Composer autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Load helper functions
require_once BASE_PATH . '/app/helpers.php';

// Load database configuration
$dbConfig = require BASE_PATH . '/config/database.php';
$dbSettings = $dbConfig['connections'][$dbConfig['default']];

// Create database connection
try {
    $database = new \Core\Database($dbSettings);
    $db = $database->connect();
    
    // Make database available globally
    $GLOBALS['db'] = $db;
    
} catch (\Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Load routes
$routes = require BASE_PATH . '/app/routes.php';

// Get URL from query string
$url = $_GET['url'] ?? '';

// Create router instance
$router = new \Core\Router();

// Add routes
foreach ($routes as $route => $params) {
    $router->add($route, $params);
}

// Dispatch request
try {
    $router->dispatch($url);
} catch (\Exception $e) {
    // Log error
    error_log('Router Error: ' . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    echo "An error occurred. Please try again later.";
    
    // In development, show detailed error
    if (env('APP_ENV') === 'development') {
        echo "<pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
    }
}
