<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>System Diagnostic Tool</h1>";

// 1. Check Autoloader
$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    echo "✅ Autoloader exists.<br>";
    require_once $autoloader;
} else {
    echo "❌ Autoloader MISSING at $autoloader<br>";
}

// 2. Check Core Files
$core_files = [
    'config/config.php',
    'core/Database.php',
    'core/Controller.php',
    'core/Router.php'
];

foreach ($core_files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ Core file exists: $file<br>";
        try {
            require_once __DIR__ . '/' . $file;
            echo "&nbsp;&nbsp;&nbsp; - Loaded successfully.<br>";
        } catch (Throwable $t) {
            echo "&nbsp;&nbsp;&nbsp; <span style='color:red;'>❌ Error loading $file: " . $t->getMessage() . "</span><br>";
        }
    } else {
        echo "❌ Core file MISSING: $file<br>";
    }
}

// 3. Check Controllers
echo "<h2>Controllers Check</h2>";
$controllers_dir = __DIR__ . '/controllers';
if (is_dir($controllers_dir)) {
    $controllers = glob($controllers_dir . '/*.php');
    foreach ($controllers as $file) {
        $basename = basename($file);
        echo "Testing $basename... ";
        try {
            // Check for syntax errors by including it
            include_once $file;
            echo "<span style='color:green;'>✅ Syntax OK</span>";
            
            // Try to instantiate AdminController if it's the one
            if ($basename === 'AdminController.php') {
                try {
                    // We need a session for AdminController constructor
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['user_id'] = 1; // Fake admin
                    $_SESSION['user_rol'] = 1;
                    
                    $admin = new AdminController();
                    echo " | <span style='color:green;'>✅ Instantiation OK</span>";
                } catch (Throwable $t) {
                    echo " | <span style='color:red;'>❌ Instantiation FAILED: " . $t->getMessage() . "</span>";
                }
            }
            echo "<br>";
        } catch (Throwable $t) {
            echo "<span style='color:red;'>❌ ERROR: " . $t->getMessage() . "</span><br>";
        }
    }
} else {
    echo "❌ Controllers directory MISSING.<br>";
}

// 4. Check Database Connection
echo "<h2>Database Connection Check</h2>";
try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "✅ Database connection SUCCESSFUL.<br>";
} catch (Throwable $t) {
    echo "❌ Database connection FAILED: " . $t->getMessage() . "<br>";
}

// 5. Session Check
echo "<h2>Session Check</h2>";
session_start();
$_SESSION['debug_test'] = time();
if (isset($_SESSION['debug_test'])) {
    echo "✅ Sessions are working. Test value: " . $_SESSION['debug_test'] . "<br>";
} else {
    echo "❌ Sessions ARE NOT working.<br>";
}

echo "<h2>PHP Info Snippet</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";
