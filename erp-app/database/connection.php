<?php
/**
 * Multi-Tenant Database Connection for ERP
 * 
 * Provides PDO connection to MySQL database with tenant support
 */

// Load SaaS configuration first
require_once __DIR__ . '/../../saas-management/config/app.php';

// Load SaaS database connection
require_once __DIR__ . '/../../saas-management/database/connection.php';

// Load tenant helper
require_once __DIR__ . '/../../saas-management/app/helpers/tenant.php';

// Use tenant-specific database if logged in, otherwise default
if (isset($_SESSION['tenant_id']) && !empty($_SESSION['tenant_id'])) {
    $db = MultiTenantDatabase::getInstance($_SESSION['tenant_id']);
} else {
    $db = MultiTenantDatabase::getInstance();
}
?> 