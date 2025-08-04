<?php
// Migration to update POS page icon
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "🔄 Updating POS page icon...\n";
    
    // Update the POS page icon to calculator
    $db->query("UPDATE pages SET icon = 'ri-calculator-line' WHERE name = 'pos'");
    
    echo "✅ POS page icon updated successfully!\n";
    echo "   - New Icon: ri-calculator-line (Calculator)\n";
    
    // Verify the update
    $page = $db->fetch("SELECT name, title, icon FROM pages WHERE name = 'pos'");
    if ($page) {
        echo "\n📋 Updated page details:\n";
        echo "   - Name: {$page['name']}\n";
        echo "   - Title: {$page['title']}\n";
        echo "   - Icon: {$page['icon']}\n";
    }
    
    echo "\n🎉 POS icon update completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 