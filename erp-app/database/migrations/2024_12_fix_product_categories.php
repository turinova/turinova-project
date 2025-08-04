<?php
// Migration to fix spelling and move product categories to master data
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Fixing product categories spelling and moving to master data...\n";
    
    // Update the product categories page
    $db->query("UPDATE pages SET title = 'Termékkategóriák', menu_order = 12 WHERE name = 'product_categories'");
    
    echo "✅ Product categories updated successfully!\n";
    echo "   - Fixed spelling: Termékkategóriák\n";
    echo "   - New menu order: 12\n";
    echo "   - Moved to master data section\n";
    
    // Verify the update
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'product_categories'");
    if ($page) {
        echo "\n📋 Updated page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Product categories migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 