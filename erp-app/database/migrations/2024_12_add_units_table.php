<?php
/**
 * Migration: Create units table
 * Date: 2024-12-XX
 */

// Load database connection
require_once __DIR__ . '/../connection.php';

try {
    // Create units table
    $sql = "
    CREATE TABLE IF NOT EXISTS `units` (
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
        $db->query("INSERT IGNORE INTO units (name, abbreviation) VALUES (?, ?)", [$unit['name'], $unit['abbreviation']]);
    }
    
    echo "✅ Default units inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating units table: " . $e->getMessage() . "\n";
}
?> 