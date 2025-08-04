<?php
// Migration to add warehouses management page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding warehouses management page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('warehouses', 'Raktárak kezelése', '/warehouses', 'ri-store-line', 17)");
    
    echo "✅ Warehouses page added successfully!\n";
    echo "   - Title: Raktárak kezelése\n";
    echo "   - Route: /warehouses\n";
    echo "   - Icon: ri-store-line\n";
    echo "   - Menu Order: 17\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'warehouses'");
    if ($page) {
        echo "\n📋 Page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Warehouses page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 