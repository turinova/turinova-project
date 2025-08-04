<?php
require_once __DIR__ . '/../helpers/auth.php';

class ShelfController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Polchelyek';
        
        // Render the shelves view
        ob_start();
        include '../app/views/shelves/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 