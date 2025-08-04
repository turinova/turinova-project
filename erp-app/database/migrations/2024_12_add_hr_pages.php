<?php
// Migration to add HR pages
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "👥 Adding HR pages...\n";
    
    // Add new pages
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES 
                ('positions', 'Beosztás', '/positions', 'ri-user-settings-line', 40),
                ('employees', 'Dolgozók', '/employees', 'ri-team-line', 41),
                ('performance', 'Teljesítmény', '/performance', 'ri-bar-chart-box-line', 42)");
    
    echo "✅ HR pages added successfully!\n";
    echo "   - Beosztás: Order 40\n";
    echo "   - Dolgozók: Order 41\n";
    echo "   - Teljesítmény: Order 42\n";
    
    // Verify the pages were added
    $pages = $db->fetchAll("SELECT id, name, title, route, icon, menu_order FROM pages WHERE name IN ('positions', 'employees', 'performance') ORDER BY menu_order");
    
    echo "\n📋 Page details:\n";
    foreach ($pages as $page) {
        echo "   - {$page['title']}: Order {$page['menu_order']}\n";
    }
    
    // Grant permissions to superuser
    $superuser = $db->fetch("SELECT id FROM users WHERE role = 'superuser' LIMIT 1");
    if ($superuser) {
        foreach ($pages as $page) {
            $db->query("INSERT IGNORE INTO user_permissions (user_id, page_id, can_access) VALUES ({$superuser['id']}, {$page['id']}, 1)");
        }
        echo "\n🔐 Permissions granted to superuser!\n";
    }
    
    echo "\n🎉 HR pages setup completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 