<?php
// Migration to add new pages: Partnerek, Szállítmányok, Beszállítói rendelések
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding new pages to navigation...\n";
    
    // Add new pages
    $newPages = [
        ['partners', 'Partnerek', '/partners', 'ri-team-line', 4],
        ['shipments', 'Szállítmányok', '/shipments', 'ri-truck-line', 5],
        ['supplier_orders', 'Beszállítói rendelések', '/supplier-orders', 'ri-shopping-cart-line', 6]
    ];
    
    foreach ($newPages as $page) {
        $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES (?, ?, ?, ?, ?)", $page);
        echo "✅ Added page: {$page[1]} ({$page[0]})\n";
    }
    
    // Verify all pages were added
    $pages = $db->fetchAll("SELECT name, title, route, icon, menu_order FROM pages WHERE name IN ('partners', 'shipments', 'supplier_orders') ORDER BY menu_order");
    
    echo "\n📋 All new pages:\n";
    foreach ($pages as $page) {
        echo "   - {$page['title']} ({$page['name']}) - {$page['route']} - {$page['icon']}\n";
    }
    
    echo "\n✅ New pages migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 