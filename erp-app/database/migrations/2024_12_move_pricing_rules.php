<?php
// Migration to move pricing rules to master data
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "📦 Moving pricing rules to master data...\n";
    
    // Update the pricing rules page
    $db->query("UPDATE pages SET menu_order = 16 WHERE name = 'pricing_rules'");
    
    echo "✅ Pricing rules moved successfully!\n";
    echo "   - New menu order: 16\n";
    echo "   - Moved to master data section\n";
    
    // Verify the update
    $page = $db->fetch("SELECT name, title, route, icon, menu_order FROM pages WHERE name = 'pricing_rules'");
    if ($page) {
        echo "\n📋 Updated page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Route: {$page['route']}\n";
        echo "   - Icon: {$page['icon']}\n";
        echo "   - Menu Order: {$page['menu_order']}\n";
    }
    
    echo "\n✅ Pricing rules migration completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 