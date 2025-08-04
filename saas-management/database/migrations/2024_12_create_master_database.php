<?php
/**
 * Create Master Database for Multi-Tenant SaaS System
 */

require_once __DIR__ . '/../../config/app.php';

try {
    // Connect to MySQL without specifying database
    $pdo = new PDO("mysql:host=localhost;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;charset=utf8mb4", SAAS_DB_USER, SAAS_DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create master database
    $pdo->query("CREATE DATABASE IF NOT EXISTS `" . SAAS_MASTER_DB_NAME . "`");
    echo "✅ Master database created: " . SAAS_MASTER_DB_NAME . "\n";
    
    // Connect to master database
    $pdo = new PDO("mysql:host=localhost;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=" . SAAS_MASTER_DB_NAME . ";charset=utf8mb4", SAAS_DB_USER, SAAS_DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tenants table
    $pdo->query("CREATE TABLE IF NOT EXISTS tenants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        identifier VARCHAR(50) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        plan VARCHAR(50) DEFAULT 'basic',
        max_users INT DEFAULT 10,
        max_storage_mb INT DEFAULT 1000,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_identifier (identifier),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Tenants table created\n";
    
    // Create tenant_users table for cross-tenant user management
    $pdo->query("CREATE TABLE IF NOT EXISTS tenant_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tenant_id INT NOT NULL,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        role ENUM('superuser', 'admin', 'user') DEFAULT 'user',
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        last_login TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
        UNIQUE KEY unique_tenant_email (tenant_id, email),
        INDEX idx_tenant_id (tenant_id),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Tenant users table created\n";
    
    // Create tenant_settings table
    $pdo->query("CREATE TABLE IF NOT EXISTS tenant_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tenant_id INT NOT NULL,
        setting_key VARCHAR(100) NOT NULL,
        setting_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
        UNIQUE KEY unique_tenant_setting (tenant_id, setting_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Tenant settings table created\n";
    
    // Create tenant_usage table for tracking usage
    $pdo->query("CREATE TABLE IF NOT EXISTS tenant_usage (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tenant_id INT NOT NULL,
        metric_name VARCHAR(100) NOT NULL,
        metric_value INT DEFAULT 0,
        recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
        INDEX idx_tenant_metric (tenant_id, metric_name),
        INDEX idx_recorded_at (recorded_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Tenant usage table created\n";
    
    // Create tenant_subscriptions table
    $pdo->query("CREATE TABLE IF NOT EXISTS tenant_subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tenant_id INT NOT NULL,
        plan_name VARCHAR(50) NOT NULL,
        status ENUM('active', 'cancelled', 'expired') DEFAULT 'active',
        start_date DATE NOT NULL,
        end_date DATE,
        price DECIMAL(10,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
        INDEX idx_tenant_status (tenant_id, status),
        INDEX idx_end_date (end_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Tenant subscriptions table created\n";
    
    echo "🎉 Master database setup completed successfully!\n";
    echo "📊 Database: " . SAAS_MASTER_DB_NAME . "\n";
    echo "📋 Tables: tenants, tenant_users, tenant_settings, tenant_usage, tenant_subscriptions\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 