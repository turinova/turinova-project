<?php
/**
 * PostgreSQL Connection Test
 * 
 * Test script to verify PostgreSQL database connection and basic functionality
 */

// Load configuration
require_once __DIR__ . '/erp-app/config/app.php';

echo "=== PostgreSQL Connection Test ===\n";
echo "Database Type: " . DB_TYPE . "\n";
echo "Host: " . DB_HOST . "\n";
echo "Port: " . DB_PORT . "\n";
echo "Database: " . DB_NAME . "\n";
echo "User: " . DB_USER . "\n";
echo "Charset: " . DB_CHARSET . "\n\n";

try {
    // Create PostgreSQL connection
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
    echo "Connecting with DSN: " . $dsn . "\n\n";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ Database connection successful!\n\n";
    
    // Test basic queries
    echo "=== Testing Basic Queries ===\n";
    
    // Test 1: Check if tables exist
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll();
    
    echo "📋 Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "  - " . $table['table_name'] . "\n";
    }
    echo "\n";
    
    // Test 2: Check users table
    if (in_array('users', array_column($tables, 'table_name'))) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "👥 Users table: " . $result['count'] . " records\n";
        
        // Show admin user
        $stmt = $pdo->query("SELECT username, email, role FROM users WHERE role = 'admin'");
        $admin = $stmt->fetch();
        if ($admin) {
            echo "   Admin user: " . $admin['username'] . " (" . $admin['email'] . ")\n";
        }
    }
    
    // Test 3: Check pages table
    if (in_array('pages', array_column($tables, 'table_name'))) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM pages");
        $result = $stmt->fetch();
        echo "📄 Pages table: " . $result['count'] . " records\n";
    }
    
    // Test 4: Check company_data table
    if (in_array('company_data', array_column($tables, 'table_name'))) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM company_data");
        $result = $stmt->fetch();
        echo "🏢 Company data: " . $result['count'] . " records\n";
    }
    
    echo "\n✅ All tests passed! PostgreSQL is working correctly.\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}
?> 