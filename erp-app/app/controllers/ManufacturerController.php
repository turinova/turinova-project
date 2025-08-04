<?php
require_once __DIR__ . '/../helpers/auth.php';

class ManufacturerController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Gyártók';
        
        // Render the manufacturers view
        ob_start();
        include '../app/views/manufacturers/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 