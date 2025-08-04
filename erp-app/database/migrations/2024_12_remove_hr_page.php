<?php
// Migration to remove HR page
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "🗑️ Removing Emberi erőforrás page...\n";
    
    // Get the HR page ID first
    $hrPage = $db->fetch("SELECT id FROM pages WHERE name = 'hr'");
    if ($hrPage) {
        // Remove user permissions for HR page
        $db->query("DELETE FROM user_permissions WHERE page_id = {$hrPage['id']}");
        echo "✅ Removed HR page permissions\n";
        
        // Remove the HR page
        $db->query("DELETE FROM pages WHERE name = 'hr'");
        echo "✅ Removed HR page from database\n";
    } else {
        echo "⚠️ HR page not found in database\n";
    }
    
    echo "\n🎉 HR page removal completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 