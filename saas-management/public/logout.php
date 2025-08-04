<?php
/**
 * SaaS Management Logout Entry Point
 */

// Load configuration first
require_once '../config/app.php';
require_once '../config/session.php';

// Start session after configuration
session_start();

// Load controller
require_once '../app/controllers/AuthController.php';

// Handle logout
$authController = new AuthController();
$authController->logout();
?> 