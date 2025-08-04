<?php
// Migration to add company data page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "🏢 Adding Cégadatok page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('company_data', 'Cégadatok', '/company-data', 'ri-building-line', 16)");
    
    echo "✅ Cégadatok page added successfully!\n";
    echo "   - Title: Cégadatok\n";
    echo "   - Route: /company-data\n";
    echo "   - Icon: ri-building-line\n";
    echo "   - Menu Order: 16\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT id, name, title, route, icon, menu_order FROM pages WHERE name = 'company_data'");
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
    
    echo "\n🎉 Cégadatok page setup completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 