<?php
/**
 * SaaS Management Dashboard Entry Point
 */

// Load configuration first
require_once '../config/app.php';

// Start session with custom name
session_name(SAAS_SESSION_NAME);
session_start();

// Load helpers
require_once '../app/helpers/auth.php';
require_once '../app/helpers/tenant.php';

// Load controller
require_once '../app/controllers/DashboardController.php';

// Create controller instance
$controller = new DashboardController();

// Handle different actions
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'add_tenant':
        $controller->addTenant();
        break;
    case 'all_tenants':
        $controller->allTenants();
        break;
    case 'edit_superuser':
        $controller->editSuperuser();
        break;
    case 'edit_permissions':
        $controller->editTenantPermissions();
        break;
    case 'get_tenant_permissions':
        $controller->getTenantPermissions();
        break;
    case 'update_tenant_permissions':
        $controller->updateTenantPermissions();
        break;
    case 'update_status':
        $controller->updateTenantStatus();
        break;
    case 'delete_tenant':
        $controller->deleteTenant();
        break;
    default:
        $controller->index();
        break;
}
?> 