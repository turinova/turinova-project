<?php
/**
 * SaaS Admin Login Entry Point
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
require_once '../app/controllers/AuthController.php';

// Create controller instance
$controller = new AuthController();

// Handle admin login
$controller->adminLogin();
?> 