<?php
/**
 * SaaS Management Registration Entry Point
 */

// Load configuration first
require_once '../config/app.php';
require_once '../config/session.php';

// Start session after configuration
session_start();

// Load database connection
require_once '../database/connection.php';

// Load helpers
require_once '../app/helpers/auth.php';
require_once '../app/helpers/tenant.php';

// Load controller
require_once '../app/controllers/AuthController.php';

// Handle registration
$authController = new AuthController();
$authController->register();
?> 