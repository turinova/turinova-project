<?php
/**
 * Migration: Add payment_methods table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
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
    
} catch (Exception $e) {
    echo "Error creating payment_methods table: " . $e->getMessage() . "\n";
}
?> 