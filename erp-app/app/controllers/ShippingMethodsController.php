<?php
require_once __DIR__ . '/../helpers/auth.php';

class ShippingMethodsController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Szállítási módok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['shipping_methods_success'])) {
            $success = $_SESSION['shipping_methods_success'];
            unset($_SESSION['shipping_methods_success']);
        }
        if (isset($_SESSION['shipping_methods_error'])) {
            $error = $_SESSION['shipping_methods_error'];
            unset($_SESSION['shipping_methods_error']);
        }
        
        // Fetch all shipping methods
        $shippingMethods = $db->fetchAll("SELECT * FROM shipping_methods ORDER BY name");
        
        ob_start();
        include '../app/views/shipping-methods/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: shipping-methods.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['shipping_methods_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: shipping-methods.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO shipping_methods (name, description) VALUES (?, ?)", 
                [$name, $description]);
            $_SESSION['shipping_methods_success'] = 'Szállítási mód sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['shipping_methods_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: shipping-methods.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: shipping-methods.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['shipping_methods_error'] = 'Érvénytelen azonosító!';
            header('Location: shipping-methods.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM shipping_methods WHERE id = ?", [$id]);
            $_SESSION['shipping_methods_success'] = 'Szállítási mód sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['shipping_methods_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: shipping-methods.php');
        exit;
    }
}
?> 