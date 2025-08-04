<?php
/**
 * ERP System - Main Entry Point
 * 
 * This file serves as the entry point for all web requests.
 * It handles routing, authentication, and loads the appropriate controllers.
 */

// Load configuration first
require_once '../config/app.php';
require_once '../config/session.php';

// Start session after configuration
session_start();

// Load database connection with error handling
try {
    require_once '../database/connection.php';
} catch (Exception $e) {
    // Log the error but don't break the application
    error_log("Database connection error: " . $e->getMessage());
    // Continue without database connection for now
}

// Load helpers
require_once '../app/helpers/validation.php';
require_once '../app/helpers/flash.php';

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove the project path from the URI
$path = str_replace('<?= BASE_PATH ?>', '', $path);
$path = str_replace('/turinova_project/erp-app/public', '', $path);
$path = str_replace('/erp-app/public', '', $path);

// Default route
if ($path === '/' || $path === '') {
    $path = '/dashboard';
}

// Load routes
require_once '../routes/web.php';
?> 