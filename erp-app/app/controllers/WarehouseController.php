<?php
require_once __DIR__ . '/../helpers/auth.php';

class WarehouseController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        global $db;
        $title = 'Raktárak kezelése';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['warehouses_success'])) {
            $success = $_SESSION['warehouses_success'];
            unset($_SESSION['warehouses_success']);
        }
        if (isset($_SESSION['warehouses_error'])) {
            $error = $_SESSION['warehouses_error'];
            unset($_SESSION['warehouses_error']);
        }
        
        // Fetch all records
        $warehouses = $db->fetchAll("SELECT * FROM warehouses WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include __DIR__ . '/../views/warehouses/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: warehouses.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $status = $_POST['status'] ?? 'active';
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['warehouses_error'] = 'A név mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        if (empty($country)) {
            $_SESSION['warehouses_error'] = 'Az ország mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        if (empty($postal_code)) {
            $_SESSION['warehouses_error'] = 'Az irányítószám mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        if (empty($city)) {
            $_SESSION['warehouses_error'] = 'A város mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        if (empty($address)) {
            $_SESSION['warehouses_error'] = 'A cím mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        // Check for duplicates
        $existing = $db->fetch("SELECT id FROM warehouses WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['warehouses_error'] = 'Ez a név már létezik!';
            header('Location: warehouses.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO warehouses (name, country, postal_code, city, address, status) VALUES (?, ?, ?, ?, ?, ?)", 
                       [$name, $country, $postal_code, $city, $address, $status]);
            $_SESSION['warehouses_success'] = 'Raktár sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['warehouses_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: warehouses.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: warehouses.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['warehouses_error'] = 'Érvénytelen azonosító!';
            header('Location: warehouses.php');
            exit;
        }
        
        try {
            $db->query("UPDATE warehouses SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['warehouses_success'] = 'Raktár sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['warehouses_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: warehouses.php');
        exit;
    }
}
?> 