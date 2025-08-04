<?php
/**
 * Update currencies table with correct data
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    echo "Updating currencies table with correct data...\n";
    
    // Delete all existing records
    $db->query("DELETE FROM currencies");
    echo "Deleted all existing currency records.\n";
    
    // Insert correct currency data
    $currencies = [
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
    
    foreach ($currencies as $currency) {
        $db->query("INSERT INTO currencies (name, exchange_rate) VALUES (?, ?)", 
            [$currency['name'], $currency['exchange_rate']]);
    }
    
    echo "Successfully inserted " . count($currencies) . " currency records.\n";
    
    // Verify the data
    $count = $db->fetch("SELECT COUNT(*) as count FROM currencies");
    echo "Table now contains " . $count['count'] . " records.\n";
    
    // Show the inserted data
    $allCurrencies = $db->fetchAll("SELECT * FROM currencies ORDER BY name");
    echo "\nInserted currencies:\n";
    foreach ($allCurrencies as $currency) {
        echo "- " . $currency['name'] . ": " . number_format($currency['exchange_rate'], 4) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 