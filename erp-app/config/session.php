<?php
/**
 * Session Configuration
 */

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS

// Session name
session_name(SESSION_NAME);

// Session lifetime (24 hours)
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);

// Session security
ini_set('session.cookie_samesite', 'Strict');

// Regenerate session ID periodically for security
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Note: session_start() is called in index.php after this config is loaded
?> 