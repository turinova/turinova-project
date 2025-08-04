<?php
/**
 * Ensure units table exists in current tenant database
 */

// Load database connection
require_once __DIR__ . '/connection.php';

try {
    // Check if units table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'units'");
    
    if (!$tableExists) {
        // Create units table
        $sql = "
        CREATE TABLE `units` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
            `abbreviation` varchar(50) NOT NULL COMMENT 'Rövidítés',
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_name` (`name`),
            UNIQUE KEY `unique_abbreviation` (`abbreviation`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $db->query($sql);
        echo "✅ Units table created successfully\n";
        
        // Insert default units
        $defaultUnits = [
            ['name' => 'Darab', 'abbreviation' => 'db'],
            ['name' => 'Kilogramm', 'abbreviation' => 'kg'],
            ['name' => 'Gramm', 'abbreviation' => 'g'],
            ['name' => 'Liter', 'abbreviation' => 'l'],
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Méter', 'abbreviation' => 'm'],
            ['name' => 'Centiméter', 'abbreviation' => 'cm'],
            ['name' => 'Milliméter', 'abbreviation' => 'mm'],
            ['name' => 'Négyzetméter', 'abbreviation' => 'm²'],
            ['name' => 'Köbméter', 'abbreviation' => 'm³']
        ];
        
        foreach ($defaultUnits as $unit) {
            $db->query("INSERT INTO units (name, abbreviation) VALUES (?, ?)", [$unit['name'], $unit['abbreviation']]);
        }
        
        echo "✅ Default units inserted successfully\n";
        
    } else {
        // Check if table is empty and insert default data if needed
        $count = $db->fetch("SELECT COUNT(*) as count FROM units")['count'];
        
        if ($count == 0) {
            echo "📋 Units table exists but is empty, inserting default data...\n";
            
            $defaultUnits = [
                ['name' => 'Darab', 'abbreviation' => 'db'],
                ['name' => 'Kilogramm', 'abbreviation' => 'kg'],
                ['name' => 'Gramm', 'abbreviation' => 'g'],
                ['name' => 'Liter', 'abbreviation' => 'l'],
                ['name' => 'Milliliter', 'abbreviation' => 'ml'],
                ['name' => 'Méter', 'abbreviation' => 'm'],
                ['name' => 'Centiméter', 'abbreviation' => 'cm'],
                ['name' => 'Milliméter', 'abbreviation' => 'mm'],
                ['name' => 'Négyzetméter', 'abbreviation' => 'm²'],
                ['name' => 'Köbméter', 'abbreviation' => 'm³']
            ];
            
            foreach ($defaultUnits as $unit) {
                $db->query("INSERT INTO units (name, abbreviation) VALUES (?, ?)", [$unit['name'], $unit['abbreviation']]);
            }
            
            echo "✅ Default units inserted successfully\n";
        } else {
            echo "✅ Units table already exists with data ($count records)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error ensuring units table: " . $e->getMessage() . "\n";
}
?> 