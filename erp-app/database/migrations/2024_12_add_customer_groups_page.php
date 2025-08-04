<?php
// Migration to add customer groups page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding customer groups page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('customer_groups', 'Vevőcsoportok', '/customer-groups', 'ri-group-line', 19)");
    
    echo "✅ Customer groups page added successfully!\n";
    echo "   - Title: Vevőcsoportok\n";
    echo "   - Route: /customer-groups\n";
    echo "   - Icon: ri-group-line\n";
    echo "   - Menu Order: 19\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'customer_groups'");
    if ($page) {
        echo "\n📋 Page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Customer groups page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 