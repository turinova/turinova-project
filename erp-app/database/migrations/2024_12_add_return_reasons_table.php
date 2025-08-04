<?php
/**
 * Migration: Add return_reasons table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
    // Create return_reasons table
    $sql = "CREATE TABLE IF NOT EXISTS return_reasons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL COMMENT 'Név',
        is_selectable TINYINT(1) DEFAULT 1 COMMENT 'Selejt (Igen/Nem)',
        is_creditable TINYINT(1) DEFAULT 1 COMMENT 'Jóváirható (Igen/Nem)',
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($sql);
    
    // Insert some default return reasons
    $defaultReturnReasons = [
        ['name' => 'Hibás termék', 'is_selectable' => 1, 'is_creditable' => 1],
        ['name' => 'Rossz méret', 'is_selectable' => 1, 'is_creditable' => 1],
        ['name' => 'Nem tetszik', 'is_selectable' => 1, 'is_creditable' => 0],
        ['name' => 'Késői szállítás', 'is_selectable' => 1, 'is_creditable' => 1],
        ['name' => 'Hibás csomagolás', 'is_selectable' => 1, 'is_creditable' => 1],
        ['name' => 'Egyéb', 'is_selectable' => 1, 'is_creditable' => 0]
    ];
    
    foreach ($defaultReturnReasons as $reason) {
        $db->query("INSERT INTO return_reasons (name, is_selectable, is_creditable) VALUES (?, ?, ?)", 
            [$reason['name'], $reason['is_selectable'], $reason['is_creditable']]);
    }
    
    echo "Return reasons table created successfully with default data!\n";
    
} catch (Exception $e) {
    echo "Error creating return_reasons table: " . $e->getMessage() . "\n";
}
?> 