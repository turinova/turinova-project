<?php
/**
 * Ensure payment_methods table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'payment_methods'");
    
    if (!$tableExists) {
        echo "Creating payment_methods table in current tenant database...\n";
        
        // Create payment_methods table
        $sql = "CREATE TABLE IF NOT EXISTS payment_methods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL COMMENT 'Megnevezés',
            description TEXT COMMENT 'Megjegyzés',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->query($sql);
        
        // Insert some default payment methods
        $defaultMethods = [
            ['name' => 'Készpénz', 'description' => 'Készpénzes fizetés'],
            ['name' => 'Bankkártya', 'description' => 'Bankkártyás fizetés'],
            ['name' => 'Átutalás', 'description' => 'Banki átutalás'],
            ['name' => 'Csekk', 'description' => 'Csekkes fizetés'],
            ['name' => 'Utánvét', 'description' => 'Utánvétes fizetés']
        ];
        
        foreach ($defaultMethods as $method) {
            $db->query("INSERT INTO payment_methods (name, description) VALUES (?, ?)", 
                [$method['name'], $method['description']]);
        }
        
        echo "Payment methods table created successfully with default data!\n";
    } else {
        echo "Payment methods table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM payment_methods");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default payment methods...\n";
            
            $defaultMethods = [
                ['name' => 'Készpénz', 'description' => 'Készpénzes fizetés'],
                ['name' => 'Bankkártya', 'description' => 'Bankkártyás fizetés'],
                ['name' => 'Átutalás', 'description' => 'Banki átutalás'],
                ['name' => 'Csekk', 'description' => 'Csekkes fizetés'],
                ['name' => 'Utánvét', 'description' => 'Utánvétes fizetés']
            ];
            
            foreach ($defaultMethods as $method) {
                $db->query("INSERT INTO payment_methods (name, description) VALUES (?, ?)", 
                    [$method['name'], $method['description']]);
            }
            
            echo "Default payment methods added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 