<?php
/**
 * Migration: Add vat table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
    // Create vat table
    $sql = "CREATE TABLE IF NOT EXISTS vat (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL COMMENT 'Megnevezés',
        rate DECIMAL(5,2) NOT NULL COMMENT 'Kulcs',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($sql);
    
    // Insert some default VAT rates
    $defaultVatRates = [
        ['name' => '0% ÁFA', 'rate' => 0.00],
        ['name' => '5% ÁFA', 'rate' => 5.00],
        ['name' => '18% ÁFA', 'rate' => 18.00],
        ['name' => '27% ÁFA', 'rate' => 27.00],
        ['name' => 'ÁFA mentes', 'rate' => 0.00]
    ];
    
    foreach ($defaultVatRates as $vat) {
        $db->query("INSERT INTO vat (name, rate) VALUES (?, ?)", 
            [$vat['name'], $vat['rate']]);
    }
    
    echo "VAT table created successfully with default data!\n";
    
} catch (Exception $e) {
    echo "Error creating vat table: " . $e->getMessage() . "\n";
}
?> 