<?php
/**
 * Shelves Entry Point
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
requirePageAccess('shelves');

// Set path for routing
$path = '/shelves';

// Handle actions
$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load controller and handle add action
    require_once '../app/controllers/ShelvesController.php';
    $controller = new ShelvesController();
    $controller->add();
} elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load controller and handle delete action
    require_once '../app/controllers/ShelvesController.php';
    $controller = new ShelvesController();
    $controller->delete();
} elseif ($action === 'get_warehouses' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // AJAX endpoint for getting warehouses
    require_once '../app/controllers/ShelvesController.php';
    $controller = new ShelvesController();
    $controller->getWarehouses();
} elseif ($action === 'get_sections' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // AJAX endpoint for getting sections
    require_once '../app/controllers/ShelvesController.php';
    $controller = new ShelvesController();
    $controller->getSections();
} elseif ($action === 'get_columns' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // AJAX endpoint for getting columns
    require_once '../app/controllers/ShelvesController.php';
    $controller = new ShelvesController();
    $controller->getColumns();
} else {
    // Load routes for normal page display
    require_once '../routes/web.php';
}
?> 