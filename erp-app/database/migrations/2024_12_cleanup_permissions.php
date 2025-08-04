<?php
// Migration to clean up user_permissions table and remove irrelevant pages
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    echo "🧹 Starting database cleanup...\n";
    
    // Step 1: Remove unnecessary columns from user_permissions table
    echo "📝 Removing unnecessary columns from user_permissions table...\n";
    
    // Check if columns exist before removing them
    $columns = $db->fetchAll("SHOW COLUMNS FROM user_permissions");
    $columnNames = array_column($columns, 'Field');
    
    $columnsToRemove = ['can_create', 'can_edit', 'can_delete', 'can_view', 'updated_at'];
    
    foreach ($columnsToRemove as $column) {
        if (in_array($column, $columnNames)) {
            $db->query("ALTER TABLE user_permissions DROP COLUMN $column");
            echo "✅ Removed column: $column\n";
        }
    }
    
    // Step 2: Remove irrelevant pages
    echo "🗑️ Removing irrelevant pages...\n";
    
    // Pages to keep (only the main ones we actually use)
    $pagesToKeep = ['dashboard', 'users'];
    
    // Get all pages
    $allPages = $db->fetchAll("SELECT id, name, title FROM pages");
    
    foreach ($allPages as $page) {
        if (!in_array($page['name'], $pagesToKeep)) {
            // Delete user permissions for this page first (due to foreign key)
            $db->query("DELETE FROM user_permissions WHERE page_id = ?", [$page['id']]);
            echo "🗑️ Removed permissions for page: {$page['title']} ({$page['name']})\n";
            
            // Delete the page
            $db->query("DELETE FROM pages WHERE id = ?", [$page['id']]);
            echo "🗑️ Removed page: {$page['title']} ({$page['name']})\n";
        }
    }
    
    // Step 3: Update remaining pages to have correct structure
    echo "🔄 Updating remaining pages...\n";
    
    // Update dashboard page
    $db->query("UPDATE pages SET 
        title = 'Vezérlőpult',
        route = '/dashboard',
        icon = 'ri-home-smile-line',
        menu_order = 1
        WHERE name = 'dashboard'");
    
    // Update users page
    $db->query("UPDATE pages SET 
        title = 'Felhasználók',
        route = '/users',
        icon = 'ri-user-line',
        menu_order = 2
        WHERE name = 'users'");
    
    // Step 4: Clean up user_permissions table structure
    echo "🔧 Optimizing user_permissions table structure...\n";
    
    // Remove unique constraint if it exists (we don't need it for simplified permissions)
    try {
        $db->query("ALTER TABLE user_permissions DROP INDEX unique_user_page");
        echo "✅ Removed unique constraint\n";
    } catch (Exception $e) {
        echo "ℹ️ Unique constraint already removed or doesn't exist\n";
    }
    
    // Step 5: Update superuser permissions for remaining pages
    echo "👑 Updating superuser permissions...\n";
    
    $superuserId = $db->fetch("SELECT id FROM users WHERE username = 'superuser'")['id'];
    $remainingPages = $db->fetchAll("SELECT id FROM pages");
    
    // Clear existing permissions for superuser
    $db->query("DELETE FROM user_permissions WHERE user_id = ?", [$superuserId]);
    
    // Grant access to remaining pages
    foreach ($remainingPages as $page) {
        $db->query("INSERT INTO user_permissions (user_id, page_id, can_access) VALUES (?, ?, 1)",
            [$superuserId, $page['id']]);
    }
    
    echo "✅ Updated superuser permissions\n";
    
    // Step 6: Verify the cleanup
    echo "🔍 Verifying cleanup results...\n";
    
    $remainingPages = $db->fetchAll("SELECT name, title FROM pages ORDER BY menu_order");
    echo "📋 Remaining pages:\n";
    foreach ($remainingPages as $page) {
        echo "   - {$page['title']} ({$page['name']})\n";
    }
    
    $permissionColumns = $db->fetchAll("SHOW COLUMNS FROM user_permissions");
    echo "📋 User permissions table columns:\n";
    foreach ($permissionColumns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\n✅ Database cleanup completed successfully!\n";
    echo "🎯 Simplified permission system with only 'can_access' column\n";
    echo "📱 Only 'dashboard' and 'users' pages remain\n";
    
} catch (Exception $e) {
    echo "❌ Error during cleanup: " . $e->getMessage() . "\n";
    echo "🔧 Please check the database manually\n";
} 