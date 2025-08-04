<?php
require_once __DIR__ . '/../helpers/auth.php';

/**
 * Dashboard Controller
 * 
 * Handles dashboard-related functionality
 */

class DashboardController {
    
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }
    
    /**
     * Display the main dashboard
     */
    public function index() {
        global $db;
        
        // Get some basic stats for the dashboard
        $totalUsers = $db->fetch("SELECT COUNT(*) as count FROM users")['count'];
        $activeUsers = $db->fetch("SELECT COUNT(*) as count FROM users WHERE status = 'active'")['count'];
        $recentLogins = $db->fetch("SELECT COUNT(*) as count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY)")['count'];
        
        // Get recent users
        $recentUsers = $db->fetchAll("
            SELECT username as name, email, role, created_at 
            FROM users 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        
        // Provide static test data for missing stats
        $stats = [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'recent_logins' => $recentLogins,
            'total_products' => 0, // Static test data
            'recent_products' => [], // Empty array for now
            'recent_users' => $recentUsers
        ];
        
        // Set page title
        $title = 'Vezérlőpult';
        
        // Render the dashboard view
        ob_start();
        include '../app/views/dashboard/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
}
?> 