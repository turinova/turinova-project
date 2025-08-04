<?php
/**
 * Migration: Add currencies table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
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
    
} catch (Exception $e) {
    echo "Error creating currencies table: " . $e->getMessage() . "\n";
}
?> 