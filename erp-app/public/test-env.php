<?php
/**
 * Environment Variables Test
 * 
 * Shows all environment variables available in DigitalOcean
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Environment Variables Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ccc; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .highlight { background-color: #ffffcc; }
    </style>
</head>
<body>
    <h1>🔍 Environment Variables Test</h1>";

echo "<div class='section'>
    <h2>📋 All Environment Variables</h2>
    <table>
        <tr><th>Variable</th><th>Value</th></tr>";

foreach ($_ENV as $key => $value) {
    $highlight = (strpos($key, 'DATABASE') !== false || strpos($key, 'DB_') !== false) ? 'class="highlight"' : '';
    echo "<tr {$highlight}><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
}

echo "</table>
</div>";

echo "<div class='section'>
    <h2>🔌 Database Connection Test</h2>";

try {
    // Load configuration
    require_once __DIR__ . '/../config/app.php';
    
    echo "<p><strong>Configuration loaded successfully</strong></p>";
    echo "<p>DB_TYPE: " . (defined('DB_TYPE') ? DB_TYPE : 'NOT DEFINED') . "</p>";
    echo "<p>DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . "</p>";
    echo "<p>DB_PORT: " . (defined('DB_PORT') ? DB_PORT : 'NOT DEFINED') . "</p>";
    echo "<p>DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . "</p>";
    echo "<p>DB_USER: " . (defined('DB_USER') ? DB_USER : 'NOT DEFINED') . "</p>";
    
    // Create database connection
    if (defined('DB_TYPE') && DB_TYPE === 'pgsql') {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    }
    
    echo "<p><strong>DSN:</strong> " . $dsn . "</p>";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<p style='color: green; font-weight: bold;'>✅ Database connection successful!</p>";
    
    // Test basic query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Users in database: <strong>" . $result['count'] . "</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Configuration error!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "</div>
</body>
</html>";
?> 