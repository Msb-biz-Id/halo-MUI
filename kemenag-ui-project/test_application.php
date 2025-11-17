<?php
/**
 * COMPREHENSIVE APPLICATION TEST SCRIPT
 * Tests if the application can actually run
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "===========================================\n";
echo "COMPREHENSIVE APPLICATION TEST\n";
echo "===========================================\n\n";

$errors = [];
$warnings = [];
$passed = 0;
$failed = 0;

// TEST 1: Check if composer autoload exists
echo "[TEST 1] Checking Composer Autoload...\n";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "  ✅ vendor/autoload.php exists\n";
    require_once __DIR__ . '/vendor/autoload.php';
    $passed++;
} else {
    echo "  ❌ vendor/autoload.php MISSING!\n";
    echo "  ⚠️  Run: composer install\n";
    $errors[] = "Composer autoload missing";
    $failed++;
}

// TEST 2: Check Core classes
echo "\n[TEST 2] Testing Core Framework Classes...\n";
$coreClasses = ['Controller', 'Model', 'Router', 'Database'];
foreach ($coreClasses as $class) {
    $fullClass = "Core\\{$class}";
    if (class_exists($fullClass)) {
        echo "  ✅ {$fullClass} loaded\n";
        $passed++;
    } else {
        echo "  ❌ {$fullClass} NOT FOUND!\n";
        $errors[] = "{$fullClass} missing";
        $failed++;
    }
}

// TEST 3: Check if config files exist
echo "\n[TEST 3] Checking Configuration Files...\n";
$configFiles = ['config/config.php', 'config/database.php', 'config/mail.php'];
foreach ($configFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "  ✅ {$file} exists\n";
        $passed++;
    } else {
        echo "  ❌ {$file} MISSING!\n";
        $errors[] = "{$file} missing";
        $failed++;
    }
}

// TEST 4: Load configuration
echo "\n[TEST 4] Loading Configuration...\n";
try {
    require_once __DIR__ . '/config/config.php';
    echo "  ✅ Configuration loaded successfully\n";
    $passed++;
} catch (Exception $e) {
    echo "  ❌ Failed to load configuration: " . $e->getMessage() . "\n";
    $errors[] = "Config load failed";
    $failed++;
}

// TEST 5: Load helpers
echo "\n[TEST 5] Loading Helper Functions...\n";
try {
    if (file_exists(__DIR__ . '/app/helpers.php')) {
        require_once __DIR__ . '/app/helpers.php';
        
        // Test if url() function exists
        if (function_exists('url')) {
            echo "  ✅ Helper functions loaded (url() exists)\n";
            $passed++;
        } else {
            echo "  ⚠️  Helper loaded but url() function not found\n";
            $warnings[] = "url() function missing";
        }
    } else {
        echo "  ❌ app/helpers.php MISSING!\n";
        $errors[] = "helpers.php missing";
        $failed++;
    }
} catch (Exception $e) {
    echo "  ❌ Failed to load helpers: " . $e->getMessage() . "\n";
    $errors[] = "Helpers load failed";
    $failed++;
}

// TEST 6: Test database configuration
echo "\n[TEST 6] Testing Database Configuration...\n";
try {
    $dbConfig = require __DIR__ . '/config/database.php';
    $dbSettings = $dbConfig['connections'][$dbConfig['default']];
    
    echo "  ℹ️  Database: {$dbSettings['database']}\n";
    echo "  ℹ️  Host: {$dbSettings['host']}\n";
    echo "  ℹ️  Username: {$dbSettings['username']}\n";
    
    if (class_exists('Core\Database')) {
        echo "  ✅ Database config loaded\n";
        $passed++;
        
        // Try to connect (will fail if DB not setup, but that's OK)
        try {
            $database = new \Core\Database($dbSettings);
            $db = $database->connect();
            echo "  ✅ Database connection successful!\n";
            $passed++;
        } catch (Exception $e) {
            echo "  ⚠️  Database connection failed (expected if DB not setup yet)\n";
            echo "     Error: " . $e->getMessage() . "\n";
            $warnings[] = "DB connection failed (may need setup)";
        }
    }
} catch (Exception $e) {
    echo "  ❌ Database config error: " . $e->getMessage() . "\n";
    $errors[] = "DB config error";
    $failed++;
}

// TEST 7: Test Model instantiation
echo "\n[TEST 7] Testing Model Classes...\n";
$testModels = ['User', 'Role', 'Category', 'Setting'];
foreach ($testModels as $modelName) {
    $modelClass = "App\\Models\\{$modelName}";
    if (class_exists($modelClass)) {
        echo "  ✅ {$modelClass} can be loaded\n";
        $passed++;
    } else {
        echo "  ❌ {$modelClass} NOT FOUND!\n";
        $errors[] = "{$modelClass} missing";
        $failed++;
    }
}

// TEST 8: Test Controller instantiation
echo "\n[TEST 8] Testing Controller Classes...\n";
$testControllers = [
    'App\Controllers\HomeController',
    'App\Controllers\AuthController',
    'App\Controllers\Admin\DashboardController'
];
foreach ($testControllers as $controllerClass) {
    if (class_exists($controllerClass)) {
        echo "  ✅ {$controllerClass} can be loaded\n";
        $passed++;
    } else {
        echo "  ❌ {$controllerClass} NOT FOUND!\n";
        $errors[] = "{$controllerClass} missing";
        $failed++;
    }
}

// TEST 9: Test Routes loading
echo "\n[TEST 9] Testing Routes Configuration...\n";
try {
    $routes = require __DIR__ . '/app/routes.php';
    if (is_array($routes)) {
        echo "  ✅ Routes loaded successfully (" . count($routes) . " routes)\n";
        $passed++;
        
        // Check a few critical routes
        $criticalRoutes = ['', 'auth/login', 'admin', 'dashboard'];
        foreach ($criticalRoutes as $route) {
            if (isset($routes[$route])) {
                echo "  ✅ Route '{$route}' defined\n";
                $passed++;
            } else {
                echo "  ⚠️  Route '{$route}' not found\n";
                $warnings[] = "Route '{$route}' missing";
            }
        }
    } else {
        echo "  ❌ Routes file did not return array!\n";
        $errors[] = "Routes format error";
        $failed++;
    }
} catch (Exception $e) {
    echo "  ❌ Failed to load routes: " . $e->getMessage() . "\n";
    $errors[] = "Routes load failed";
    $failed++;
}

// TEST 10: Check critical directories
echo "\n[TEST 10] Checking Directory Structure...\n";
$criticalDirs = [
    'app/controllers',
    'app/models',
    'app/views',
    'app/services',
    'core',
    'config',
    'public',
    'db'
];
foreach ($criticalDirs as $dir) {
    if (is_dir(__DIR__ . '/' . $dir)) {
        echo "  ✅ {$dir}/ exists\n";
        $passed++;
    } else {
        echo "  ❌ {$dir}/ MISSING!\n";
        $errors[] = "{$dir}/ missing";
        $failed++;
    }
}

// TEST 11: Check .htaccess
echo "\n[TEST 11] Checking Apache Configuration...\n";
if (file_exists(__DIR__ . '/public/.htaccess')) {
    echo "  ✅ public/.htaccess exists\n";
    $passed++;
} else {
    echo "  ⚠️  public/.htaccess MISSING (mod_rewrite won't work)\n";
    $warnings[] = ".htaccess missing";
}

// TEST 12: Check environment file
echo "\n[TEST 12] Checking Environment Configuration...\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "  ✅ .env file exists\n";
    $passed++;
} else {
    echo "  ⚠️  .env file MISSING (copy from .env.example)\n";
    $warnings[] = ".env missing";
}

if (file_exists(__DIR__ . '/.env.example')) {
    echo "  ✅ .env.example exists\n";
    $passed++;
} else {
    echo "  ❌ .env.example MISSING!\n";
    $errors[] = ".env.example missing";
    $failed++;
}

// FINAL SUMMARY
echo "\n===========================================\n";
echo "TEST SUMMARY\n";
echo "===========================================\n";
echo "✅ Passed:   {$passed} tests\n";
echo "❌ Failed:   {$failed} tests\n";
echo "⚠️  Warnings: " . count($warnings) . " warnings\n";
echo "\n";

if (!empty($errors)) {
    echo "🚨 CRITICAL ERRORS:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  WARNINGS (May affect functionality):\n";
    foreach ($warnings as $warning) {
        echo "   - {$warning}\n";
    }
    echo "\n";
}

if ($failed === 0) {
    echo "🎉 ALL TESTS PASSED! Application structure is correct!\n";
    echo "\n";
    echo "Next steps:\n";
    echo "1. Run: composer install\n";
    echo "2. Copy .env.example to .env and configure\n";
    echo "3. Create database and import db/schema.sql\n";
    echo "4. Access via web browser\n";
} else {
    echo "❌ TESTS FAILED! Fix errors above before proceeding.\n";
}

echo "\n===========================================\n";
?>
