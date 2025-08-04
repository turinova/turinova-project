<?php
// Migration to move partners page and rename it
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Moving partners page to master data...\n";
    
    // Update the partners page to be under master data
    $db->query("UPDATE pages SET title = 'Partnerek kezelése', menu_order = 11 WHERE name = 'partners'");
    
    echo "✅ Partners page updated successfully!\n";
    echo "   - New title: Partnerek kezelése\n";
    echo "   - New menu order: 11\n";
    echo "   - Moved to master data section\n";
    
    // Verify the update
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'partners'");
    if ($page) {
        echo "\n📋 Updated page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Partners page migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 