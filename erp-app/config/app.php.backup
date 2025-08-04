<?php
/**
 * Application Configuration
 * 
 * Environment-based configuration for local development and production deployment
 */

// Environment detection
$is_local = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8888']);
$is_production = $_SERVER['HTTP_HOST'] === 'app.turinova.hu' || 
                 strpos($_SERVER['HTTP_HOST'] ?? '', 'ondigitalocean.app') !== false ||
                 strpos($_SERVER['HTTP_HOST'] ?? '', 'turinova') !== false;

// Dynamic path configuration
if ($is_production) {
    // Production environment
    if (!defined('BASE_PATH')) define('BASE_PATH', '');
    if (!defined('ASSETS_PATH')) define('ASSETS_PATH', '/assets');
    if (!defined('ERP_BASE_URL')) define('ERP_BASE_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'app.turinova.hu'));
    if (!defined('SAAS_BASE_URL')) define('SAAS_BASE_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'admin.turinova.hu') . '/admin');
    if (!defined('APP_ENV')) define('APP_ENV', 'production');
} else {
    // Local development environment
    if (!defined('BASE_PATH')) define('BASE_PATH', '/Turinova_project/erp-app/public');
    if (!defined('ASSETS_PATH')) define('ASSETS_PATH', '/Turinova_project/erp-app/public/assets');
    if (!defined('ERP_BASE_URL')) define('ERP_BASE_URL', 'http://localhost:8888/Turinova_project/erp-app/public');
    if (!defined('SAAS_BASE_URL')) define('SAAS_BASE_URL', 'http://localhost:8888/Turinova_project/saas-management/public');
    if (!defined('APP_ENV')) define('APP_ENV', 'development');
}

// Application settings
if (!defined('APP_NAME')) define('APP_NAME', 'Turinova ERP');
if (!defined('APP_VERSION')) define('APP_VERSION', '3.0');

// Database configuration - Use environment variables in production
if (!defined('DB_HOST')) define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', $_ENV['DB_NAME'] ?? 'turinova_erp');
if (!defined('DB_USER')) define('DB_USER', $_ENV['DB_USER'] ?? 'root');
if (!defined('DB_PASS')) define('DB_PASS', $_ENV['DB_PASS'] ?? 'root');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// Performance settings
if (!defined('CACHE_ENABLED')) define('CACHE_ENABLED', $is_production);
if (!defined('DEBUG_MODE')) define('DEBUG_MODE', !$is_production);
if (!defined('LOG_LEVEL')) define('LOG_LEVEL', $is_production ? 'ERROR' : 'DEBUG');

// Error reporting
if ($is_production) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Timezone
date_default_timezone_set('Europe/Budapest');

// Security
if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', 'csrf_token');
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'turinova_erp_session');
if (!defined('SAAS_SESSION_NAME')) define('SAAS_SESSION_NAME', 'turinova_saas_session');
?>
