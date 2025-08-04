<?php
require_once __DIR__ . '/../helpers/auth.php';

class ShipmentController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Szállítmányok';
        
        // Render the shipments view
        ob_start();
        include '../app/views/shipments/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 