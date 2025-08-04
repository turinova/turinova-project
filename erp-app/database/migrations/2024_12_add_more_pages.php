<?php
// Migration to add more pages: Raktár, Ügyfelek, Árazási szabályok, Termékkatárógirák
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding more pages to navigation...\n";
    
    // Add new pages
    $newPages = [
        ['warehouse', 'Raktár', '/warehouse', 'ri-store-line', 7],
        ['customers', 'Ügyfelek', '/customers', 'ri-user-line', 8],
        ['pricing_rules', 'Árazási szabályok', '/pricing-rules', 'ri-price-tag-line', 9],
        ['product_categories', 'Termékkatárógirák', '/product-categories', 'ri-folder-line', 10]
    ];
    
    foreach ($newPages as $page) {
        $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES (?, ?, ?, ?, ?)", $page);
        echo "✅ Added page: {$page[1]} ({$page[0]})\n";
    }
    
    // Verify all pages were added
    $pages = $db->fetchAll("SELECT name, title, route, icon, menu_order FROM pages WHERE name IN ('warehouse', 'customers', 'pricing_rules', 'product_categories') ORDER BY menu_order");
    
    echo "\n📋 All new pages:\n";
    foreach ($pages as $page) {
        echo "   - {$page['title']} ({$page['name']}) - {$page['route']} - {$page['icon']}\n";
    }
    
    echo "\n✅ More pages migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 