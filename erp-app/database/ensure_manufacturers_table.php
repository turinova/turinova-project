<?php
/**
 * Ensure manufacturers table exists in current tenant database
 */

// Load database connection
require_once __DIR__ . '/connection.php';

try {
    // Check if manufacturers table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'manufacturers'");
    
    if (!$tableExists) {
        // Create manufacturers table
        $sql = "
        CREATE TABLE `manufacturers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
            `country` varchar(100) NOT NULL COMMENT 'Ország',
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $db->query($sql);
        echo "✅ Manufacturers table created successfully\n";
        
        // Insert default manufacturers
        $defaultManufacturers = [
            ['name' => 'Samsung', 'country' => 'Dél-Korea'],
            ['name' => 'Apple', 'country' => 'USA'],
            ['name' => 'Huawei', 'country' => 'Kína'],
            ['name' => 'Xiaomi', 'country' => 'Kína'],
            ['name' => 'LG', 'country' => 'Dél-Korea'],
            ['name' => 'Sony', 'country' => 'Japán'],
            ['name' => 'Panasonic', 'country' => 'Japán'],
            ['name' => 'Philips', 'country' => 'Hollandia'],
            ['name' => 'Bosch', 'country' => 'Németország'],
            ['name' => 'Siemens', 'country' => 'Németország']
        ];
        
        foreach ($defaultManufacturers as $manufacturer) {
            $db->query("INSERT INTO manufacturers (name, country) VALUES (?, ?)", [$manufacturer['name'], $manufacturer['country']]);
        }
        
        echo "✅ Default manufacturers inserted successfully\n";
        
    } else {
        // Check if table is empty and insert default data if needed
        $count = $db->fetch("SELECT COUNT(*) as count FROM manufacturers")['count'];
        
        if ($count == 0) {
            echo "📋 Manufacturers table exists but is empty, inserting default data...\n";
            
            $defaultManufacturers = [
                ['name' => 'Samsung', 'country' => 'Dél-Korea'],
                ['name' => 'Apple', 'country' => 'USA'],
                ['name' => 'Huawei', 'country' => 'Kína'],
                ['name' => 'Xiaomi', 'country' => 'Kína'],
                ['name' => 'LG', 'country' => 'Dél-Korea'],
                ['name' => 'Sony', 'country' => 'Japán'],
                ['name' => 'Panasonic', 'country' => 'Japán'],
                ['name' => 'Philips', 'country' => 'Hollandia'],
                ['name' => 'Bosch', 'country' => 'Németország'],
                ['name' => 'Siemens', 'country' => 'Németország']
            ];
            
            foreach ($defaultManufacturers as $manufacturer) {
                $db->query("INSERT INTO manufacturers (name, country) VALUES (?, ?)", [$manufacturer['name'], $manufacturer['country']]);
            }
            
            echo "✅ Default manufacturers inserted successfully\n";
        } else {
            echo "✅ Manufacturers table already exists with data ($count records)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error ensuring manufacturers table: " . $e->getMessage() . "\n";
}
?> 