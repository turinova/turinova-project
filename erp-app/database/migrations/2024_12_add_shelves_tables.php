<?php
/**
 * Migration: Add shelves tables
 * 
 * This migration creates the hierarchical shelves system:
 * - warehouse_sections (Sor)
 * - warehouse_columns (Oszlop) 
 * - warehouse_shelves (Polc)
 */

// Load database connection
require_once __DIR__ . '/../connection.php';

try {
    // Create warehouse_sections table (Sor)
    $db->query("
    CREATE TABLE IF NOT EXISTS `warehouse_sections` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `warehouse_id` int(11) NOT NULL COMMENT 'Raktár ID',
        `name` varchar(100) NOT NULL COMMENT 'Sor neve',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_warehouse_id` (`warehouse_id`),
        UNIQUE KEY `unique_section_per_warehouse` (`warehouse_id`, `name`),
        CONSTRAINT `fk_sections_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "✅ Warehouse sections table created successfully\n";
    
    // Create warehouse_columns table (Oszlop)
    $db->query("
    CREATE TABLE IF NOT EXISTS `warehouse_columns` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `section_id` int(11) NOT NULL COMMENT 'Sor ID',
        `name` varchar(100) NOT NULL COMMENT 'Oszlop neve',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_section_id` (`section_id`),
        UNIQUE KEY `unique_column_per_section` (`section_id`, `name`),
        CONSTRAINT `fk_columns_section` FOREIGN KEY (`section_id`) REFERENCES `warehouse_sections` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "✅ Warehouse columns table created successfully\n";
    
    // Create warehouse_shelves table (Polc)
    $db->query("
    CREATE TABLE IF NOT EXISTS `warehouse_shelves` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `column_id` int(11) NOT NULL COMMENT 'Oszlop ID',
        `name` varchar(100) NOT NULL COMMENT 'Polc neve',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_column_id` (`column_id`),
        UNIQUE KEY `unique_shelf_per_column` (`column_id`, `name`),
        CONSTRAINT `fk_shelves_column` FOREIGN KEY (`column_id`) REFERENCES `warehouse_columns` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "✅ Warehouse shelves table created successfully\n";
    
    // Insert default data for existing warehouses
    $warehouses = $db->fetchAll("SELECT id, name FROM warehouses WHERE is_active = 1");
    
    foreach ($warehouses as $warehouse) {
        // Create sections for each warehouse
        $sections = [
            ['name' => 'A Sor'],
            ['name' => 'B Sor'],
            ['name' => 'C Sor']
        ];
        
        foreach ($sections as $section) {
            $db->query("INSERT IGNORE INTO warehouse_sections (warehouse_id, name) VALUES (?, ?)", 
                       [$warehouse['id'], $section['name']]);
        }
    }
    
    // Get all sections and create columns
    $sections = $db->fetchAll("SELECT id, name FROM warehouse_sections WHERE is_active = 1");

    foreach ($sections as $section) {
        // Create columns for each section
        $columns = [
            ['name' => '1 Oszlop'],
            ['name' => '2 Oszlop'],
            ['name' => '3 Oszlop']
        ];
        
        foreach ($columns as $column) {
            $db->query("INSERT IGNORE INTO warehouse_columns (section_id, name) VALUES (?, ?)", 
                [$section['id'], $column['name']]);
        }
    }

    // Get all columns and create shelves
    $columns = $db->fetchAll("SELECT id, name FROM warehouse_columns WHERE is_active = 1");

    foreach ($columns as $column) {
        // Create shelves for each column
        $shelves = [
            ['name' => '1 Polc'],
            ['name' => '2 Polc'],
            ['name' => '3 Polc']
        ];
        
        foreach ($shelves as $shelf) {
            $db->query("INSERT IGNORE INTO warehouse_shelves (column_id, name) VALUES (?, ?)", 
                       [$column['id'], $shelf['name']]);
        }
    }
    
    echo "✅ Default shelves data inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating shelves tables: " . $e->getMessage() . "\n";
}
?> 