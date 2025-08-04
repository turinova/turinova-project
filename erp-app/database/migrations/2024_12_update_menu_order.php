<?php
// Migration to update menu order for correct hierarchy
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "🔄 Updating menu order for correct hierarchy...\n";
    
    // Update menu order for main pages
    $db->query("UPDATE pages SET menu_order = 1 WHERE name = 'dashboard'");
    $db->query("UPDATE pages SET menu_order = 2 WHERE name = 'sales'");
    $db->query("UPDATE pages SET menu_order = 3 WHERE name = 'returns'");
    $db->query("UPDATE pages SET menu_order = 4 WHERE name = 'offers'");
    $db->query("UPDATE pages SET menu_order = 5 WHERE name = 'pos'");
    
    echo "✅ Menu order updated successfully!\n";
    echo "   - Vezérlőpult: Order 1\n";
    echo "   - Értékesítések: Order 2\n";
    echo "   - Visszaáru: Order 3\n";
    echo "   - Ajánlatok: Order 4\n";
    echo "   - POS: Order 5\n";
    
    // Verify the updates
    $pages = $db->fetchAll("SELECT name, title, menu_order FROM pages WHERE name IN ('dashboard', 'sales', 'returns', 'offers', 'pos') ORDER BY menu_order");
    
    echo "\n📋 Updated menu hierarchy:\n";
    foreach ($pages as $page) {
        echo "   - {$page['title']}: Order {$page['menu_order']}\n";
    }
    
    echo "\n🎉 Menu hierarchy updated successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 