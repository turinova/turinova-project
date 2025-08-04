<?php
// Migration to rename products page and move it to master data
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Moving products page to master data...\n";
    
    // Update the products page
    $db->query("UPDATE pages SET title = 'Termékek kezelése', menu_order = 13 WHERE name = 'products'");
    
    echo "✅ Products page updated successfully!\n";
    echo "   - New title: Termékek kezelése\n";
    echo "   - New menu order: 13\n";
    echo "   - Moved to master data section\n";
    
    // Verify the update
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'products'");
    if ($page) {
        echo "\n📋 Updated page details:\n";
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