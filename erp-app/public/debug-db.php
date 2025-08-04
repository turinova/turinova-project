<?php
/**
 * Database Debug Script
 * 
 * Shows environment variables and connection details
 */

// Set content type for better display
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ccc; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🔍 Database Debug Information</h1>";

echo "<div class='section'>
    <h2>📋 Environment Variables</h2>
    <table>
        <tr><th>Variable</th><th>Value</th></tr>
        <tr><td>DATABASE_URL</td><td>" . (isset($_ENV['DATABASE_URL']) ? 'SET' : 'NOT SET') . "</td></tr>
        <tr><td>DB_TYPE</td><td>" . ($_ENV['DB_TYPE'] ?? 'NOT SET') . "</td></tr>
        <tr><td>DB_HOST</td><td>" . ($_ENV['DB_HOST'] ?? 'NOT SET') . "</td></tr>
        <tr><td>DB_PORT</td><td>" . ($_ENV['DB_PORT'] ?? 'NOT SET') . "</td></tr>
        <tr><td>DB_NAME</td><td>" . ($_ENV['DB_NAME'] ?? 'NOT SET') . "</td></tr>
        <tr><td>DB_USER</td><td>" . ($_ENV['DB_USER'] ?? 'NOT SET') . "</td></tr>
        <tr><td>DB_PASS</td><td>" . (isset($_ENV['DB_PASS']) ? 'SET' : 'NOT SET') . "</td></tr>
    </table>
</div>";

echo "<div class='section'>
    <h2>🔌 Connection Test</h2>";

try {
    // Load configuration
    require_once __DIR__ . '/../config/app.php';
    
    echo "<p><strong>Configuration loaded successfully</strong></p>";
    echo "<p>DB_TYPE: " . DB_TYPE . "</p>";
    echo "<p>DB_HOST: " . DB_HOST . "</p>";
    echo "<p>DB_PORT: " . DB_PORT . "</p>";
    echo "<p>DB_NAME: " . DB_NAME . "</p>";
    echo "<p>DB_USER: " . DB_USER . "</p>";
    
    // Create database connection
    if (DB_TYPE === 'pgsql') {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    }
    
    echo "<p><strong>DSN:</strong> " . $dsn . "</p>";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<p class='success'>✅ Database connection successful!</p>";
    
    // Test basic query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Users in database: <strong>" . $result['count'] . "</strong></p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Configuration error!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "</div>
</body>
</html>";
?> 