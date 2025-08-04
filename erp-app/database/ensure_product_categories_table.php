<?php
/**
 * Ensure product_categories table exists in current tenant database
 */

// Load database connection
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'product_categories'");
    
    if (!$tableExists) {
        // Create table
        $sql = "
        CREATE TABLE `product_categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
            `description` varchar(500) DEFAULT NULL COMMENT 'Megjegyzés',
            `parent_id` int(11) DEFAULT NULL COMMENT 'Szülő kategória',
            `level` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Szint (1: főkategória, 2: alkategória)',
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_name_per_parent` (`name`, `parent_id`),
            KEY `idx_parent_id` (`parent_id`),
            KEY `idx_level` (`level`),
            KEY `idx_is_active` (`is_active`),
            FOREIGN KEY (`parent_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $db->query($sql);
        echo "✅ Product categories table created successfully\n";
        
        // Insert default main categories (level 1)
        $mainCategories = [
            ['name' => 'Elektronika', 'description' => 'Elektronikai termékek'],
            ['name' => 'Ruházat', 'description' => 'Ruházati termékek'],
            ['name' => 'Könyvek', 'description' => 'Könyvek és irodalom'],
            ['name' => 'Sport', 'description' => 'Sport és fitness termékek'],
            ['name' => 'Otthon', 'description' => 'Otthoni termékek']
        ];
        
        foreach ($mainCategories as $category) {
            $db->query("INSERT INTO product_categories (name, description, level) VALUES (?, ?, 1)", 
                       [$category['name'], $category['description']]);
        }
        
        echo "✅ Default main categories inserted successfully\n";
        
        // Insert default sub-categories (level 2)
        // Get the main categories first
        $mainCats = $db->fetchAll("SELECT id, name FROM product_categories WHERE level = 1");
        
        $subCategories = [
            // Elektronika sub-categories
            ['name' => 'Telefonok', 'description' => 'Mobiltelefonok', 'parent_name' => 'Elektronika'],
            ['name' => 'Laptopok', 'description' => 'Hordozható számítógépek', 'parent_name' => 'Elektronika'],
            ['name' => 'Tabletek', 'description' => 'Táblagépek', 'parent_name' => 'Elektronika'],
            
            // Ruházat sub-categories
            ['name' => 'Férfi ruházat', 'description' => 'Férfi ruházati termékek', 'parent_name' => 'Ruházat'],
            ['name' => 'Női ruházat', 'description' => 'Női ruházati termékek', 'parent_name' => 'Ruházat'],
            ['name' => 'Gyermek ruházat', 'description' => 'Gyermek ruházati termékek', 'parent_name' => 'Ruházat'],
            
            // Könyvek sub-categories
            ['name' => 'Szépirodalom', 'description' => 'Szépirodalmi könyvek', 'parent_name' => 'Könyvek'],
            ['name' => 'Tudományos', 'description' => 'Tudományos könyvek', 'parent_name' => 'Könyvek'],
            ['name' => 'Gyermekkönyvek', 'description' => 'Gyermekkönyvek', 'parent_name' => 'Könyvek'],
            
            // Sport sub-categories
            ['name' => 'Futás', 'description' => 'Futófelszerelések', 'parent_name' => 'Sport'],
            ['name' => 'Fitness', 'description' => 'Fitness termékek', 'parent_name' => 'Sport'],
            ['name' => 'Csapatjátékok', 'description' => 'Csapatjáték felszerelések', 'parent_name' => 'Sport'],
            
            // Otthon sub-categories
            ['name' => 'Konyha', 'description' => 'Konyhai eszközök', 'parent_name' => 'Otthon'],
            ['name' => 'Dekoráció', 'description' => 'Otthoni dekorációk', 'parent_name' => 'Otthon'],
            ['name' => 'Kert', 'description' => 'Kerti eszközök', 'parent_name' => 'Otthon']
        ];
        
        foreach ($subCategories as $subCat) {
            // Find parent category
            $parent = null;
            foreach ($mainCats as $mainCat) {
                if ($mainCat['name'] === $subCat['parent_name']) {
                    $parent = $mainCat;
                    break;
                }
            }
            
            if ($parent) {
                $db->query("INSERT INTO product_categories (name, description, parent_id, level) VALUES (?, ?, ?, 2)", 
                           [$subCat['name'], $subCat['description'], $parent['id']]);
            }
        }
        
        echo "✅ Default sub-categories inserted successfully\n";
        
    } else {
        // Check if table is empty
        $count = $db->fetch("SELECT COUNT(*) as count FROM product_categories")['count'];
        
        if ($count == 0) {
            echo "📋 Product categories table exists but is empty, inserting default data...\n";
            
            // Insert default main categories (level 1)
            $mainCategories = [
                ['name' => 'Elektronika', 'description' => 'Elektronikai termékek'],
                ['name' => 'Ruházat', 'description' => 'Ruházati termékek'],
                ['name' => 'Könyvek', 'description' => 'Könyvek és irodalom'],
                ['name' => 'Sport', 'description' => 'Sport és fitness termékek'],
                ['name' => 'Otthon', 'description' => 'Otthoni termékek']
            ];
            
            foreach ($mainCategories as $category) {
                $db->query("INSERT INTO product_categories (name, description, level) VALUES (?, ?, 1)", 
                           [$category['name'], $category['description']]);
            }
            
            echo "✅ Default main categories inserted successfully\n";
            
            // Insert default sub-categories (level 2)
            // Get the main categories first
            $mainCats = $db->fetchAll("SELECT id, name FROM product_categories WHERE level = 1");
            
            $subCategories = [
                // Elektronika sub-categories
                ['name' => 'Telefonok', 'description' => 'Mobiltelefonok', 'parent_name' => 'Elektronika'],
                ['name' => 'Laptopok', 'description' => 'Hordozható számítógépek', 'parent_name' => 'Elektronika'],
                ['name' => 'Tabletek', 'description' => 'Táblagépek', 'parent_name' => 'Elektronika'],
                
                // Ruházat sub-categories
                ['name' => 'Férfi ruházat', 'description' => 'Férfi ruházati termékek', 'parent_name' => 'Ruházat'],
                ['name' => 'Női ruházat', 'description' => 'Női ruházati termékek', 'parent_name' => 'Ruházat'],
                ['name' => 'Gyermek ruházat', 'description' => 'Gyermek ruházati termékek', 'parent_name' => 'Ruházat'],
                
                // Könyvek sub-categories
                ['name' => 'Szépirodalom', 'description' => 'Szépirodalmi könyvek', 'parent_name' => 'Könyvek'],
                ['name' => 'Tudományos', 'description' => 'Tudományos könyvek', 'parent_name' => 'Könyvek'],
                ['name' => 'Gyermekkönyvek', 'description' => 'Gyermekkönyvek', 'parent_name' => 'Könyvek'],
                
                // Sport sub-categories
                ['name' => 'Futás', 'description' => 'Futófelszerelések', 'parent_name' => 'Sport'],
                ['name' => 'Fitness', 'description' => 'Fitness termékek', 'parent_name' => 'Sport'],
                ['name' => 'Csapatjátékok', 'description' => 'Csapatjáték felszerelések', 'parent_name' => 'Sport'],
                
                // Otthon sub-categories
                ['name' => 'Konyha', 'description' => 'Konyhai eszközök', 'parent_name' => 'Otthon'],
                ['name' => 'Dekoráció', 'description' => 'Otthoni dekorációk', 'parent_name' => 'Otthon'],
                ['name' => 'Kert', 'description' => 'Kerti eszközök', 'parent_name' => 'Otthon']
            ];
            
            foreach ($subCategories as $subCat) {
                // Find parent category
                $parent = null;
                foreach ($mainCats as $mainCat) {
                    if ($mainCat['name'] === $subCat['parent_name']) {
                        $parent = $mainCat;
                        break;
                    }
                }
                
                if ($parent) {
                    $db->query("INSERT INTO product_categories (name, description, parent_id, level) VALUES (?, ?, ?, 2)", 
                               [$subCat['name'], $subCat['description'], $parent['id']]);
                }
            }
            
            echo "✅ Default sub-categories inserted successfully\n";
            
        } else {
            echo "✅ Product categories table already exists with data ($count records)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error ensuring product_categories table: " . $e->getMessage() . "\n";
}
?> 