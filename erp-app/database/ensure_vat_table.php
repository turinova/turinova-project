<?php
/**
 * Ensure vat table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'vat'");
    
    if (!$tableExists) {
        echo "Creating vat table in current tenant database...\n";
        
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
    } else {
        echo "VAT table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM vat");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default VAT rates...\n";
            
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
            
            echo "Default VAT rates added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 