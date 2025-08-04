<?php
/**
 * Migration: Add shipping_methods table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
    // Create shipping_methods table
    $sql = "CREATE TABLE IF NOT EXISTS shipping_methods (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL COMMENT 'Megnevezés',
        description TEXT COMMENT 'Megjegyzés',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($sql);
    
    // Insert some default shipping methods
    $defaultShippingMethods = [
        ['name' => 'Személyes átvétel', 'description' => 'Termék átvétele a boltban'],
        ['name' => 'Házhozszállítás', 'description' => 'Szállítás a megadott címre'],
        ['name' => 'Postai szállítás', 'description' => 'Magyar Posta szállítás'],
        ['name' => 'Futárszolgálat', 'description' => 'Expressz futárszolgálat'],
        ['name' => 'Személyes szállítás', 'description' => 'Személyes szállítás a vevőnek']
    ];
    
    foreach ($defaultShippingMethods as $method) {
        $db->query("INSERT INTO shipping_methods (name, description) VALUES (?, ?)", 
            [$method['name'], $method['description']]);
    }
    
    echo "Shipping methods table created successfully with default data!\n";
    
} catch (Exception $e) {
    echo "Error creating shipping_methods table: " . $e->getMessage() . "\n";
}
?> 