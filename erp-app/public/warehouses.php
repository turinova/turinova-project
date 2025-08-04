<?php
/**
 * Warehouses Entry Point
 */

// Load configuration first
require_once '../config/app.php';
require_once '../config/session.php';

// Start session after configuration
session_start();

// Load database connection
require_once '../database/connection.php';

// Load helpers
require_once '../app/helpers/validation.php';
require_once '../app/helpers/flash.php';
require_once '../app/helpers/auth.php';

// Require authentication and page access
requirePageAccess('warehouses');

// Set path for routing
$path = '/warehouses';

// Handle actions
$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load controller and handle add action
    require_once '../app/controllers/WarehouseController.php';
    $controller = new WarehouseController();
    $controller->add();
} elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load controller and handle delete action
    require_once '../app/controllers/WarehouseController.php';
    $controller = new WarehouseController();
    $controller->delete();
} else {
    // Load routes for normal page display
    require_once '../routes/web.php';
}
?> 