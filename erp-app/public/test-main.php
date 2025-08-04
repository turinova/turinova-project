<?php
/**
 * Main Application Test
 * 
 * Tests the main application flow step by step
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Main Application Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .step { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <h1>🔍 Main Application Test</h1>";

// Step 1: Load configuration
echo "<div class='step'>";
echo "<h3>Step 1: Loading Configuration</h3>";
try {
    require_once '../config/app.php';
    echo "<p class='success'>✅ Configuration loaded successfully</p>";
    echo "<p>DB_TYPE: " . DB_TYPE . "</p>";
    echo "<p>DB_HOST: " . DB_HOST . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Configuration failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 2: Load session configuration
echo "<div class='step'>";
echo "<h3>Step 2: Loading Session Configuration</h3>";
try {
    require_once '../config/session.php';
    echo "<p class='success'>✅ Session configuration loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Session configuration failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 3: Start session
echo "<div class='step'>";
echo "<h3>Step 3: Starting Session</h3>";
try {
    session_start();
    echo "<p class='success'>✅ Session started successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Session start failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 4: Load database connection
echo "<div class='step'>";
echo "<h3>Step 4: Loading Database Connection</h3>";
try {
    require_once '../database/connection.php';
    echo "<p class='success'>✅ Database connection loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 5: Load helpers
echo "<div class='step'>";
echo "<h3>Step 5: Loading Helpers</h3>";
try {
    require_once '../app/helpers/validation.php';
    echo "<p class='success'>✅ Validation helper loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Validation helper failed: " . $e->getMessage() . "</p>";
}

try {
    require_once '../app/helpers/flash.php';
    echo "<p class='success'>✅ Flash helper loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Flash helper failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 6: Test routing
echo "<div class='step'>";
echo "<h3>Step 6: Testing Routing</h3>";
try {
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Remove the project path from the URI
    $path = str_replace('<?= BASE_PATH ?>', '', $path);
    $path = str_replace('/turinova_project/erp-app/public', '', $path);
    $path = str_replace('/erp-app/public', '', $path);
    
    // Default route
    if ($path === '/' || $path === '') {
        $path = '/dashboard';
    }
    
    echo "<p class='success'>✅ Routing processed successfully</p>";
    echo "<p>Request URI: " . $request_uri . "</p>";
    echo "<p>Processed Path: " . $path . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Routing failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 7: Load routes
echo "<div class='step'>";
echo "<h3>Step 7: Loading Routes</h3>";
try {
    require_once '../routes/web.php';
    echo "<p class='success'>✅ Routes loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Routes failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

echo "<h2>🎉 All Steps Completed!</h2>";
echo "<p>If you see this message, the main application should work correctly.</p>";
echo "</body></html>";
?> 