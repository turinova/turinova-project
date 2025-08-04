<?php
/**
 * Update User Permissions Entry Point
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

// Load controller and call method
require_once '../app/controllers/UserController.php';

$controller = new UserController();
$controller->updatePermissions();
?> 