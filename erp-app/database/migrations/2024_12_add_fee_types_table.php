<?php
/**
 * Migration: Add fee_types table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
    // Create fee_types table
    $sql = "CREATE TABLE IF NOT EXISTS fee_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL COMMENT 'Név',
        type VARCHAR(100) NOT NULL COMMENT 'Típus',
        net_price DECIMAL(10,2) NOT NULL COMMENT 'Nettó ár',
        vat_id INT NOT NULL COMMENT 'ÁFA ID',
        gross_price DECIMAL(10,2) NOT NULL COMMENT 'Bruttó ár',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (vat_id) REFERENCES vat(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($sql);
    
    // Get the first VAT record (HUF with 0% rate)
    $vatRecord = $db->fetch("SELECT id, rate FROM vat WHERE name = 'HUF' LIMIT 1");
    if (!$vatRecord) {
        // If no HUF record, get the first available VAT record
        $vatRecord = $db->fetch("SELECT id, rate FROM vat LIMIT 1");
    }
    
    if ($vatRecord) {
        $defaultVatId = $vatRecord['id'];
        
        // Insert some default fee types
        $defaultFeeTypes = [
            ['name' => 'Alapdíj', 'type' => 'Díj', 'net_price' => 1000.00],
            ['name' => 'Kezelési díj', 'type' => 'Díj', 'net_price' => 500.00],
            ['name' => 'Szállítási díj', 'type' => 'Szállítás', 'net_price' => 2000.00],
            ['name' => 'Kedvezmény', 'type' => 'Kedvezmény', 'net_price' => -500.00],
            ['name' => 'Késedelmi kamat', 'type' => 'Kamat', 'net_price' => 100.00]
        ];
        
        foreach ($defaultFeeTypes as $fee) {
            // Calculate gross price based on VAT rate
            $vatRate = $vatRecord['rate'];
            $grossPrice = $fee['net_price'] * (1 + ($vatRate / 100));
            
            $db->query("INSERT INTO fee_types (name, type, net_price, vat_id, gross_price) VALUES (?, ?, ?, ?, ?)", 
                [$fee['name'], $fee['type'], $fee['net_price'], $defaultVatId, $grossPrice]);
        }
        
        echo "Fee types table created successfully with default data!\n";
    } else {
        echo "No VAT records found. Please create VAT records first.\n";
    }
    
} catch (Exception $e) {
    echo "Error creating fee_types table: " . $e->getMessage() . "\n";
}
?> 