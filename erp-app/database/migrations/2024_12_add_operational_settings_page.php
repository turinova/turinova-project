<?php
// Migration to add operational settings page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "⚙️ Adding Működési beállítások page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('operational_settings', 'Működési beállítások', '/operational-settings', 'ri-settings-3-line', 15)");
    
    echo "✅ Működési beállítások page added successfully!\n";
    echo "   - Title: Működési beállítások\n";
    echo "   - Route: /operational-settings\n";
    echo "   - Icon: ri-settings-3-line\n";
    echo "   - Menu Order: 15\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT id, name, title, route, icon, menu_order FROM pages WHERE name = 'operational_settings'");
    if ($page) {
        echo "\n📋 Page details:\n";
        echo "   - ID: {$page['id']}\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    // Grant permissions to superuser
    $superuser = $db->fetch("SELECT id FROM users WHERE role = 'superuser' LIMIT 1");
    if ($superuser && $page) {
        $db->query("INSERT IGNORE INTO user_permissions (user_id, page_id, can_access) VALUES ({$superuser['id']}, {$page['id']}, 1)");
        echo "\n🔐 Permissions granted to superuser!\n";
    }
    
    echo "\n🎉 Működési beállítások page setup completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 