<?php
/**
 * Manufacturers Entry Point
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
requirePageAccess('manufacturers');

// Check if this is an add or delete action
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call add method
        require_once '../app/controllers/ManufacturersController.php';
        $controller = new ManufacturersController();
        $controller->add();
        exit;
    }
    
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call delete method
        require_once '../app/controllers/ManufacturersController.php';
        $controller = new ManufacturersController();
        $controller->delete();
        exit;
    }
}

// Set path for routing
$path = '/manufacturers';

// Load routes
require_once '../routes/web.php';
?> 