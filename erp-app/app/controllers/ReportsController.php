<?php
require_once __DIR__ . '/../helpers/auth.php';

class ReportsController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Riport';
        
        // Render the reports view
        ob_start();
        include '../app/views/reports/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 