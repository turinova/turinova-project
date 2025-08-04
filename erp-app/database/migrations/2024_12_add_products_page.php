<?php
// Migration to add products page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding products page to navigation...\n";
    
    // Add products page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('products', 'Termékek', '/products', 'ri-shopping-bag-line', 3)");
    
    echo "✅ Products page added successfully!\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT * FROM pages WHERE name = 'products'");
    if ($page) {
        echo "📋 Page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Products page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 