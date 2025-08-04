<?php
/**
 * Session Configuration for SaaS Management
 */

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 86400); // 24 hours
ini_set('session.cookie_lifetime', 86400); // 24 hours

// Set session name
session_name(SAAS_SESSION_NAME);

// Set session save path
$sessionPath = SAAS_STORAGE_PATH . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
ini_set('session.save_handler', 'files');
ini_set('session.save_path', $sessionPath);

// Session security settings
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cache_limiter', 'nocache');
?> 