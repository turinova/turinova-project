<?php
/**
 * Authentication Helper Functions - Multi-Tenant ERP
 */

// Load SaaS configuration first
require_once __DIR__ . '/../../../saas-management/config/app.php';

require_once __DIR__ . '/../../../saas-management/app/helpers/tenant.php';

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    // Check if user is logged in and has tenant context
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        return false;
    }
    
    if (!isset($_SESSION['tenant_id']) || empty($_SESSION['tenant_id'])) {
        return false;
    }
    
    // Additional check - ensure session hasn't been destroyed
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }
    
    // Check if session ID is still valid
    if (empty(session_id())) {
        return false;
    }
    
    return true;
}

/**
 * Require authentication - redirect to login if not logged in
 */
function requireAuth() {
    // Set cache-busting headers to prevent browser caching
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    if (!isLoggedIn()) {
        // Clear any existing session data
        $_SESSION = array();
        session_destroy();
        
        // Clear session cookies
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }
        
        // Redirect to login page
        header('Location: ' . ERP_BASE_URL . '/login.php');
        exit;
    }
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 * @return string
 */
function getCurrentUserName() {
    return $_SESSION['user_name'] ?? 'User';
}

/**
 * Get current user role
 * @return string
 */
function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? 'user';
}

/**
 * Check if current user has access to a specific page
 * @param string $pageName
 * @return bool
 */
function hasPageAccess($pageName) {
    if (!isLoggedIn()) {
        return false;
    }
    
    try {
        $tenantDb = getTenantDatabase();
        $hasAccess = $tenantDb->fetch("SELECT 1 FROM user_permissions up
                                       JOIN pages p ON up.page_id = p.id
                                       WHERE up.user_id = ? AND p.name = ? AND up.can_access = 1",
                                       [getCurrentUserId(), $pageName]);
        return $hasAccess ? true : false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Require page access - redirect to login if user doesn't have access
 * @param string $pageName
 */
function requirePageAccess($pageName) {
    requireAuth();
    requireTenantAuth();
    
    if (!hasPageAccess($pageName)) {
        // User is logged in but doesn't have access to this page
        header('Location: ' . ERP_BASE_URL . '/dashboard.php');
        exit;
    }
} 