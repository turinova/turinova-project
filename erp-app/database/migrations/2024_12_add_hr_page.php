<?php
// Migration to add human resources page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "👥 Adding Emberi erőforrás page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('hr', 'Emberi erőforrás', '/hr', 'ri-team-line', 7)");
    
    echo "✅ Emberi erőforrás page added successfully!\n";
    echo "   - Title: Emberi erőforrás\n";
    echo "   - Route: /hr\n";
    echo "   - Icon: ri-team-line\n";
    echo "   - Menu Order: 7\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT id, name, title, route, icon, menu_order FROM pages WHERE name = 'hr'");
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
    
    echo "\n🎉 Emberi erőforrás page setup completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 