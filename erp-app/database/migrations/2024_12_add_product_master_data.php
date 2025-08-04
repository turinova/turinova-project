<?php
// Migration to add new product master data pages and rename product categories
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding new product master data pages...\n";
    
    // Add new pages
    $newPages = [
        ['manufacturers', 'Gyártók', '/manufacturers', 'ri-building-line', 14],
        ['units', 'Egységek', '/units', 'ri-ruler-line', 15]
    ];
    
    foreach ($newPages as $page) {
        $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES (?, ?, ?, ?, ?)", $page);
        echo "✅ Added page: {$page[1]} ({$page[0]})\n";
    }
    
    // Rename product categories
    $db->query("UPDATE pages SET title = 'Kategóriák' WHERE name = 'product_categories'");
    echo "✅ Renamed product categories to: Kategóriák\n";
    
    // Verify all pages
    $pages = $db->fetchAll("SELECT name, title, route, icon, menu_order FROM pages WHERE name IN ('manufacturers', 'units', 'product_categories') ORDER BY menu_order");
    
    echo "\n📋 All product master data pages:\n";
    foreach ($pages as $page) {
        echo "   - {$page['title']} ({$page['name']}) - {$page['route']} - {$page['icon']}\n";
    }
    
    echo "\n✅ Product master data migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 