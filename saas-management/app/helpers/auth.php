<?php
/**
 * Authentication Helper Functions - Multi-Tenant SaaS
 */

require_once __DIR__ . '/tenant.php';

/**
 * Check if user is logged in (tenant user or SaaS admin)
 * @return bool
 */
function isLoggedIn() {
    // Check if SaaS admin is logged in
    if (isset($_SESSION['is_saas_admin']) && $_SESSION['is_saas_admin'] && isset($_SESSION['saas_user_id'])) {
        return true;
    }
    
    // Check if tenant user is logged in
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
 * Check if user is SaaS admin
 * @return bool
 */
function isSaaSAdmin() {
    return isset($_SESSION['is_saas_admin']) && $_SESSION['is_saas_admin'] && isset($_SESSION['saas_user_id']);
}

/**
 * Require authentication - redirect to appropriate login if not logged in
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
        
        // Redirect to appropriate login page
        if (strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false) {
            // SaaS admin login
            header('Location: admin_login.php');
        } else {
            // Tenant login
            header('Location: <?= SAAS_BASE_URL ?>/login.php');
        }
        exit;
    }
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId() {
    if (isSaaSAdmin()) {
        return $_SESSION['saas_user_id'] ?? null;
    }
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 * @return string
 */
function getCurrentUserName() {
    if (isSaaSAdmin()) {
        return $_SESSION['saas_user_name'] ?? 'SaaS Admin';
    }
    return $_SESSION['user_name'] ?? 'User';
}

/**
 * Get current user role
 * @return string
 */
function getCurrentUserRole() {
    if (isSaaSAdmin()) {
        return $_SESSION['saas_user_role'] ?? 'admin';
    }
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
        header('Location: <?= SAAS_BASE_URL ?>/dashboard.php');
        exit;
    }
}
?> 