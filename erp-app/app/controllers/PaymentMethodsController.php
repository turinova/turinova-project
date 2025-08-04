<?php
require_once __DIR__ . '/../helpers/auth.php';

class PaymentMethodsController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Fizetési módok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['payment_methods_success'])) {
            $success = $_SESSION['payment_methods_success'];
            unset($_SESSION['payment_methods_success']);
        }
        if (isset($_SESSION['payment_methods_error'])) {
            $error = $_SESSION['payment_methods_error'];
            unset($_SESSION['payment_methods_error']);
        }
        
        // Fetch all payment methods
        $paymentMethods = $db->fetchAll("SELECT * FROM payment_methods ORDER BY name");
        
        ob_start();
        include '../app/views/payment-methods/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: payment-methods.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['payment_methods_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: payment-methods.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO payment_methods (name, description) VALUES (?, ?)", 
                [$name, $description]);
            $_SESSION['payment_methods_success'] = 'Fizetési mód sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['payment_methods_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: payment-methods.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: payment-methods.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['payment_methods_error'] = 'Érvénytelen azonosító!';
            header('Location: payment-methods.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM payment_methods WHERE id = ?", [$id]);
            $_SESSION['payment_methods_success'] = 'Fizetési mód sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['payment_methods_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: payment-methods.php');
        exit;
    }
} 