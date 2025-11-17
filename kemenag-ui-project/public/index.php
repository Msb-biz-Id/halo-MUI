<?php

/**
 * Front Controller
 * Entry point for all requests
 */

// Start session
session_start();

// Load configuration and autoloader
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Load routes
require_once __DIR__ . '/../app/routes.php';

// Get URL from query string
$url = $_GET['url'] ?? '';

// Create router instance
$router = new \Core\Router();

// Add routes
foreach ($routes as $route => $params) {
    $router->add($route, $params);
}

// Dispatch request
$router->dispatch($url);
