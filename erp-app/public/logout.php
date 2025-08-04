<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/../config/app.php';

// Clear all session variables first
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Clear any session cookies with proper path and domain
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/');
}

// Clear any other potential session cookies
$cookies = $_COOKIE;
foreach ($cookies as $name => $value) {
    if (strpos($name, 'PHPSESSID') !== false || strpos($name, 'session') !== false) {
        setcookie($name, '', time() - 3600, '/');
    }
}

// Set cache-busting headers to prevent browser caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Force a new session to prevent any residual data
session_start();
session_destroy();

// Redirect to login page with a clean URL
header('Location: ' . ERP_BASE_URL . '/login.php');
exit; 