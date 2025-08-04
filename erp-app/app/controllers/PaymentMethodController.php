<?php
require_once __DIR__ . '/../helpers/auth.php';

class PaymentMethodController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Fizetési módok';
        
        // Render the payment methods view
        ob_start();
        include '../app/views/payment-methods/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
} 