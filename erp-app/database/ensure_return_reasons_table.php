<?php
/**
 * Ensure return_reasons table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'return_reasons'");
    
    if (!$tableExists) {
        echo "Creating return_reasons table in current tenant database...\n";
        
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
    } else {
        echo "Return reasons table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM return_reasons");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default return reasons...\n";
            
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
            
            echo "Default return reasons added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 