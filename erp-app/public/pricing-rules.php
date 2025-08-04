<?php
/**
 * Pricing Rules Entry Point
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
requirePageAccess('pricing_rules');

// Set path for routing
$path = '/pricing-rules';

// Load routes
require_once '../routes/web.php';
?> 