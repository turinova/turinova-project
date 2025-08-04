<?php
/**
 * Ensure shipping_methods table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'shipping_methods'");
    
    if (!$tableExists) {
        echo "Creating shipping_methods table in current tenant database...\n";
        
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
    } else {
        echo "Shipping methods table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM shipping_methods");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default shipping methods...\n";
            
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
            
            echo "Default shipping methods added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 