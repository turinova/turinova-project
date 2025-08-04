<?php
/**
 * SaaS Management Configuration
 * 
 * Environment-based configuration for local development and production deployment
 */

// Environment detection
$is_local = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8888']);
$is_production = $_SERVER['HTTP_HOST'] === 'admin.turinova.hu' || 
                 strpos($_SERVER['HTTP_HOST'] ?? '', 'ondigitalocean.app') !== false ||
                 strpos($_SERVER['HTTP_HOST'] ?? '', 'turinova') !== false;

// Dynamic path configuration
if ($is_production) {
    // Production environment
    if (!defined('SAAS_BASE_PATH')) define('SAAS_BASE_PATH', '');
    if (!defined('ASSETS_PATH')) define('ASSETS_PATH', '/assets');
    if (!defined('SAAS_BASE_URL')) define('SAAS_BASE_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'admin.turinova.hu'));
    if (!defined('ERP_BASE_URL')) define('ERP_BASE_URL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'app.turinova.hu'));
    if (!defined('SAAS_APP_ENV')) define('SAAS_APP_ENV', 'production');
} else {
    // Local development environment
    if (!defined('SAAS_BASE_PATH')) define('SAAS_BASE_PATH', '/Turinova_project/saas-management/public');
    if (!defined('ASSETS_PATH')) define('ASSETS_PATH', '/Turinova_project/erp-app/public/assets');
    if (!defined('SAAS_BASE_URL')) define('SAAS_BASE_URL', 'http://localhost:8888/Turinova_project/saas-management/public');
    if (!defined('ERP_BASE_URL')) define('ERP_BASE_URL', 'http://localhost:8888/Turinova_project/erp-app/public');
    if (!defined('SAAS_APP_ENV')) define('SAAS_APP_ENV', 'development');
}

// Application settings
if (!defined('SAAS_APP_NAME')) define('SAAS_APP_NAME', 'Turinova SaaS Management');
if (!defined('SAAS_APP_VERSION')) define('SAAS_APP_VERSION', '3.0');

// Multi-tenant database configuration - Use environment variables in production
if (!defined('SAAS_DB_HOST')) define('SAAS_DB_HOST', $_ENV['SAAS_DB_HOST'] ?? 'localhost');
if (!defined('SAAS_DB_USER')) define('SAAS_DB_USER', $_ENV['SAAS_DB_USER'] ?? 'root');
if (!defined('SAAS_DB_PASS')) define('SAAS_DB_PASS', $_ENV['SAAS_DB_PASS'] ?? 'root');
if (!defined('SAAS_DB_CHARSET')) define('SAAS_DB_CHARSET', 'utf8mb4');

// Tenant database naming convention
if (!defined('SAAS_TENANT_DB_PREFIX')) define('SAAS_TENANT_DB_PREFIX', 'turinova_');
if (!defined('SAAS_TENANT_DB_SUFFIX')) define('SAAS_TENANT_DB_SUFFIX', '_erp');

// Master database for tenant management
if (!defined('SAAS_MASTER_DB_NAME')) define('SAAS_MASTER_DB_NAME', 'turinova_master');

// Default database (fallback)
if (!defined('SAAS_DEFAULT_DB_NAME')) define("SAAS_DEFAULT_DB_NAME", "turinova_sample_erp");

// Performance settings
if (!defined('SAAS_CACHE_ENABLED')) define('SAAS_CACHE_ENABLED', $is_production);
if (!defined('SAAS_DEBUG_MODE')) define('SAAS_DEBUG_MODE', !$is_production);
if (!defined('SAAS_LOG_LEVEL')) define('SAAS_LOG_LEVEL', $is_production ? 'ERROR' : 'DEBUG');

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
if (!defined('SAAS_CSRF_TOKEN_NAME')) define('SAAS_CSRF_TOKEN_NAME', 'saas_csrf_token');
if (!defined('SAAS_SESSION_NAME')) define('SAAS_SESSION_NAME', 'turinova_saas_session');

// Multi-tenant session settings
if (!defined('SAAS_TENANT_SESSION_PREFIX')) define('SAAS_TENANT_SESSION_PREFIX', 'turinova_saas_tenant_');
?>
