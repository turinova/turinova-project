<?php
require_once __DIR__ . '/../helpers/auth.php';

class VatController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'ÁFA kulcsok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['vat_success'])) {
            $success = $_SESSION['vat_success'];
            unset($_SESSION['vat_success']);
        }
        if (isset($_SESSION['vat_error'])) {
            $error = $_SESSION['vat_error'];
            unset($_SESSION['vat_error']);
        }
        
        // Fetch all VAT rates
        $vatRates = $db->fetchAll("SELECT * FROM vat ORDER BY rate, name");
        
        ob_start();
        include '../app/views/vat/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: vat.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $rate = trim($_POST['rate'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['vat_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: vat.php');
            exit;
        }
        
        if (!is_numeric($rate) || $rate < 0 || $rate > 100) {
            $_SESSION['vat_error'] = 'A kulcs értéke 0 és 100 között kell legyen!';
            header('Location: vat.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO vat (name, rate) VALUES (?, ?)", 
                [$name, $rate]);
            $_SESSION['vat_success'] = 'ÁFA kulcs sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['vat_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: vat.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: vat.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['vat_error'] = 'Érvénytelen azonosító!';
            header('Location: vat.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM vat WHERE id = ?", [$id]);
            $_SESSION['vat_success'] = 'ÁFA kulcs sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['vat_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: vat.php');
        exit;
    }
} 