<?php
/**
 * Database Connection
 * 
 * Supports both MySQL and PostgreSQL databases
 */

require_once __DIR__ . '/../config/app.php';

try {
    // Determine database type and create appropriate DSN
    if (DB_TYPE === 'pgsql') {
        // PostgreSQL connection
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } else {
        // MySQL connection (default)
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    
    // Set timezone for the connection
    if (DB_TYPE === 'pgsql') {
        $pdo->exec("SET timezone = 'Europe/Budapest'");
    } else {
        $pdo->exec("SET time_zone = '+01:00'");
    }
    
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please try again later.");
    }
}
?> 