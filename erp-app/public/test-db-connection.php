<?php
/**
 * Database Connection Test
 * 
 * Simple test to verify PostgreSQL database connection
 */

// Load configuration
require_once __DIR__ . '/../config/app.php';

// Set content type for better display
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
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
    <h1>🔍 Database Connection Test</h1>";

echo "<div class='section'>
    <h2>📋 Configuration</h2>
    <table>
        <tr><th>Setting</th><th>Value</th></tr>
        <tr><td>Database Type</td><td>" . DB_TYPE . "</td></tr>
        <tr><td>Host</td><td>" . DB_HOST . "</td></tr>
        <tr><td>Port</td><td>" . DB_PORT . "</td></tr>
        <tr><td>Database</td><td>" . DB_NAME . "</td></tr>
        <tr><td>User</td><td>" . DB_USER . "</td></tr>
        <tr><td>Charset</td><td>" . DB_CHARSET . "</td></tr>
    </table>
</div>";

try {
    // Create database connection
    if (DB_TYPE === 'pgsql') {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    }
    
    echo "<div class='section'>
        <h2>🔌 Connection Test</h2>
        <p><strong>DSN:</strong> " . $dsn . "</p>";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<p class='success'>✅ Database connection successful!</p>";
    
    // Test basic queries
    echo "<h2>📊 Database Content Test</h2>";
    
    // Test 1: Check if tables exist
    if (DB_TYPE === 'pgsql') {
        $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    } else {
        $stmt = $pdo->query("SHOW TABLES");
    }
    $tables = $stmt->fetchAll();
    
    echo "<h3>📋 Tables Found (" . count($tables) . "):</h3>";
    echo "<table>";
    echo "<tr><th>Table Name</th></tr>";
    foreach ($tables as $table) {
        $tableName = DB_TYPE === 'pgsql' ? $table['table_name'] : $table['Tables_in_' . DB_NAME];
        echo "<tr><td>" . $tableName . "</td></tr>";
    }
    echo "</table>";
    
    // Test 2: Check users table
    $tableNames = array_column($tables, DB_TYPE === 'pgsql' ? 'table_name' : 'Tables_in_' . DB_NAME);
    if (in_array('users', $tableNames)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<h3>👥 Users Table</h3>";
        echo "<p>Total users: <strong>" . $result['count'] . "</strong></p>";
        
        // Show admin user
        $stmt = $pdo->query("SELECT username, email, role FROM users WHERE role = 'admin' LIMIT 1");
        $admin = $stmt->fetch();
        if ($admin) {
            echo "<p>Admin user: <strong>" . $admin['username'] . "</strong> (" . $admin['email'] . ")</p>";
        }
    }
    
    // Test 3: Check pages table
    if (in_array('pages', $tableNames)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM pages");
        $result = $stmt->fetch();
        echo "<h3>📄 Pages Table</h3>";
        echo "<p>Total pages: <strong>" . $result['count'] . "</strong></p>";
    }
    
    // Test 4: Check company_data table
    if (in_array('company_data', $tableNames)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM company_data");
        $result = $stmt->fetch();
        echo "<h3>🏢 Company Data Table</h3>";
        echo "<p>Records: <strong>" . $result['count'] . "</strong></p>";
    }
    
    echo "<p class='success'>✅ All tests passed! Database is working correctly.</p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
}

echo "</div>
</body>
</html>";
?> 