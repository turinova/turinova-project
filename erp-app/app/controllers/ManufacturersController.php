<?php
require_once __DIR__ . '/../helpers/auth.php';

class ManufacturersController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Gyártók';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['manufacturers_success'])) {
            $success = $_SESSION['manufacturers_success'];
            unset($_SESSION['manufacturers_success']);
        }
        if (isset($_SESSION['manufacturers_error'])) {
            $error = $_SESSION['manufacturers_error'];
            unset($_SESSION['manufacturers_error']);
        }
        
        // Fetch all manufacturers
        $manufacturers = $db->fetchAll("SELECT * FROM manufacturers WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include __DIR__ . '/../views/manufacturers/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manufacturers.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['manufacturers_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: manufacturers.php');
            exit;
        }
        
        if (empty($country)) {
            $_SESSION['manufacturers_error'] = 'Az ország mező kitöltése kötelező!';
            header('Location: manufacturers.php');
            exit;
        }
        
        // Check for duplicates
        $existing = $db->fetch("SELECT id FROM manufacturers WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['manufacturers_error'] = 'Ez a megnevezés már létezik!';
            header('Location: manufacturers.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO manufacturers (name, country) VALUES (?, ?)", [$name, $country]);
            $_SESSION['manufacturers_success'] = 'Gyártó sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['manufacturers_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: manufacturers.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manufacturers.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['manufacturers_error'] = 'Érvénytelen azonosító!';
            header('Location: manufacturers.php');
            exit;
        }
        
        try {
            $db->query("UPDATE manufacturers SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['manufacturers_success'] = 'Gyártó sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['manufacturers_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: manufacturers.php');
        exit;
    }
}
?> 