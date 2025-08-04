<?php
/**
 * Ensure currencies table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'currencies'");
    
    if (!$tableExists) {
        echo "Creating currencies table in current tenant database...\n";
        
        // Create currencies table
        $sql = "CREATE TABLE IF NOT EXISTS currencies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL COMMENT 'Pénznem',
            exchange_rate DECIMAL(10,4) NOT NULL DEFAULT 1.0000 COMMENT 'Átváltási ráta',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->query($sql);
        
        // Insert 10 most common currencies with realistic exchange rates
        $defaultCurrencies = [
            ['name' => 'HUF', 'exchange_rate' => 1.0000],
            ['name' => 'EUR', 'exchange_rate' => 385.50],
            ['name' => 'USD', 'exchange_rate' => 349.25],
            ['name' => 'GBP', 'exchange_rate' => 445.80],
            ['name' => 'CHF', 'exchange_rate' => 395.30],
            ['name' => 'JPY', 'exchange_rate' => 2.35],
            ['name' => 'CNY', 'exchange_rate' => 48.50],
            ['name' => 'CAD', 'exchange_rate' => 255.40],
            ['name' => 'AUD', 'exchange_rate' => 230.60],
            ['name' => 'NZD', 'exchange_rate' => 215.80]
        ];
        
        foreach ($defaultCurrencies as $currency) {
            $db->query("INSERT INTO currencies (name, exchange_rate) VALUES (?, ?)", 
                [$currency['name'], $currency['exchange_rate']]);
        }
        
        echo "Currencies table created successfully with default data!\n";
    } else {
        echo "Currencies table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM currencies");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default currencies...\n";
            
            $defaultCurrencies = [
                ['name' => 'HUF', 'exchange_rate' => 1.0000],
                ['name' => 'EUR', 'exchange_rate' => 385.50],
                ['name' => 'USD', 'exchange_rate' => 349.25],
                ['name' => 'GBP', 'exchange_rate' => 445.80],
                ['name' => 'CHF', 'exchange_rate' => 395.30],
                ['name' => 'JPY', 'exchange_rate' => 2.35],
                ['name' => 'CNY', 'exchange_rate' => 48.50],
                ['name' => 'CAD', 'exchange_rate' => 255.40],
                ['name' => 'AUD', 'exchange_rate' => 230.60],
                ['name' => 'NZD', 'exchange_rate' => 215.80]
            ];
            
            foreach ($defaultCurrencies as $currency) {
                $db->query("INSERT INTO currencies (name, exchange_rate) VALUES (?, ?)", 
                    [$currency['name'], $currency['exchange_rate']]);
            }
            
            echo "Default currencies added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 