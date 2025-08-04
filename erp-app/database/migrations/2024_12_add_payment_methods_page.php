<?php
// Migration to add payment methods page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Adding payment methods page...\n";
    
    // Add new page
    $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('payment_methods', 'Fizetési módok', '/payment-methods', 'ri-bank-card-line', 18)");
    
    echo "✅ Payment methods page added successfully!\n";
    echo "   - Title: Fizetési módok\n";
    echo "   - Route: /payment-methods\n";
    echo "   - Icon: ri-bank-card-line\n";
    echo "   - Menu Order: 18\n";
    
    // Verify the page was added
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'payment_methods'");
    if ($page) {
        echo "\n📋 Page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Payment methods page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 