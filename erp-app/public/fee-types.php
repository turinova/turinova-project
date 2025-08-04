<?php
/**
 * Fee Types Entry Point
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
requirePageAccess('fee_types');

// Check if this is an action request
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call add method
        require_once '../app/controllers/FeeTypesController.php';
        $controller = new FeeTypesController();
        $controller->add();
        exit;
    }
    
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call delete method
        require_once '../app/controllers/FeeTypesController.php';
        $controller = new FeeTypesController();
        $controller->delete();
        exit;
    }
    
    if ($action === 'get_vat_options') {
        // Load the controller and call getVatOptions method
        require_once '../app/controllers/FeeTypesController.php';
        $controller = new FeeTypesController();
        $controller->getVatOptions();
        exit;
    }
    
    if ($action === 'calculate_gross_price' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call calculateGrossPrice method
        require_once '../app/controllers/FeeTypesController.php';
        $controller = new FeeTypesController();
        $controller->calculateGrossPrice();
        exit;
    }
}

// Set path for routing
$path = '/fee-types';

// Load routes
require_once '../routes/web.php';
?> 