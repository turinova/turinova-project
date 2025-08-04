<?php
// Migration to add shelves page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding shelves page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('shelves', 'Polchelyek', '/shelves', 'ri-layout-grid-line', 20)");
    
    echo "✅ Shelves page added successfully!\n";
    echo "   - Title: Polchelyek\n";
    echo "   - Route: /shelves\n";
    echo "   - Icon: ri-layout-grid-line\n";
    echo "   - Menu Order: 20\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'shelves'");
    if ($page) {
        echo "\n📋 Page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Shelves page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 