<?php
require_once __DIR__ . '/../helpers/auth.php';

class CurrenciesController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Pénznemek';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['currencies_success'])) {
            $success = $_SESSION['currencies_success'];
            unset($_SESSION['currencies_success']);
        }
        if (isset($_SESSION['currencies_error'])) {
            $error = $_SESSION['currencies_error'];
            unset($_SESSION['currencies_error']);
        }
        
        // Fetch all currencies
        $currencies = $db->fetchAll("SELECT * FROM currencies ORDER BY name");
        
        ob_start();
        include '../app/views/currencies/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: currencies.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $exchange_rate = trim($_POST['exchange_rate'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['currencies_error'] = 'A pénznem mező kitöltése kötelező!';
            header('Location: currencies.php');
            exit;
        }
        
        if (!is_numeric($exchange_rate) || $exchange_rate <= 0) {
            $_SESSION['currencies_error'] = 'Az átváltási ráta értéke pozitív szám kell legyen!';
            header('Location: currencies.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO currencies (name, exchange_rate) VALUES (?, ?)", 
                [$name, $exchange_rate]);
            $_SESSION['currencies_success'] = 'Pénznem sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['currencies_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: currencies.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: currencies.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['currencies_error'] = 'Érvénytelen azonosító!';
            header('Location: currencies.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM currencies WHERE id = ?", [$id]);
            $_SESSION['currencies_success'] = 'Pénznem sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['currencies_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: currencies.php');
        exit;
    }
} 