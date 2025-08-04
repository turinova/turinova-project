<?php
require_once __DIR__ . '/../helpers/auth.php';

class PerformanceController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Teljesítmény';
        
        // Render the performance view
        ob_start();
        include '../app/views/performance/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 