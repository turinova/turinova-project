<?php
/**
 * Migration: Create warehouses table
 * Date: 2024-12-XX
 */

// Load database connection
require_once __DIR__ . '/../connection.php';

try {
    // Create table SQL
    $sql = "
    CREATE TABLE IF NOT EXISTS `warehouses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL COMMENT 'Név',
        `country` varchar(100) NOT NULL COMMENT 'Ország',
        `postal_code` varchar(20) NOT NULL COMMENT 'Irányítószám',
        `city` varchar(100) NOT NULL COMMENT 'Város',
        `address` varchar(255) NOT NULL COMMENT 'Utca, Házszám',
        `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'Státusz',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->query($sql);
    echo "✅ Warehouses table created successfully\n";
    
    // Insert default data
    $defaultData = [
        [
            'name' => 'Fő Raktár',
            'country' => 'Magyarország',
            'postal_code' => '1111',
            'city' => 'Budapest',
            'address' => 'Kossuth utca 1.',
            'status' => 'active'
        ],
        [
            'name' => 'Debreceni Raktár',
            'country' => 'Magyarország',
            'postal_code' => '4000',
            'city' => 'Debrecen',
            'address' => 'Piac utca 15.',
            'status' => 'active'
        ],
        [
            'name' => 'Szegedi Raktár',
            'country' => 'Magyarország',
            'postal_code' => '6720',
            'city' => 'Szeged',
            'address' => 'Dugonics tér 13.',
            'status' => 'active'
        ]
    ];
    
    foreach ($defaultData as $item) {
        $db->query("INSERT IGNORE INTO warehouses (name, country, postal_code, city, address, status) VALUES (?, ?, ?, ?, ?, ?)", 
                   [$item['name'], $item['country'], $item['postal_code'], $item['city'], $item['address'], $item['status']]);
    }
    
    echo "✅ Default warehouses data inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating warehouses table: " . $e->getMessage() . "\n";
}
?> 