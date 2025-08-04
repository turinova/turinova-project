<?php
/**
 * Migration: Create manufacturers table
 * Date: 2024-12-XX
 */

// Load database connection
require_once __DIR__ . '/../connection.php';

try {
    // Create manufacturers table
    $sql = "
    CREATE TABLE IF NOT EXISTS `manufacturers` (
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
        $db->query("INSERT IGNORE INTO manufacturers (name, country) VALUES (?, ?)", [$manufacturer['name'], $manufacturer['country']]);
    }
    
    echo "✅ Default manufacturers inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating manufacturers table: " . $e->getMessage() . "\n";
}
?> 