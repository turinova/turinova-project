<?php
require_once __DIR__ . '/../helpers/auth.php';

class UnitsController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Egységek';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['units_success'])) {
            $success = $_SESSION['units_success'];
            unset($_SESSION['units_success']);
        }
        if (isset($_SESSION['units_error'])) {
            $error = $_SESSION['units_error'];
            unset($_SESSION['units_error']);
        }
        
        // Fetch all units
        $units = $db->fetchAll("SELECT * FROM units WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include '../app/views/units/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: units.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $abbreviation = trim($_POST['abbreviation'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['units_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: units.php');
            exit;
        }
        
        if (empty($abbreviation)) {
            $_SESSION['units_error'] = 'A rövidítés mező kitöltése kötelező!';
            header('Location: units.php');
            exit;
        }
        
        // Check for duplicates
        $existing = $db->fetch("SELECT id FROM units WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['units_error'] = 'Ez a megnevezés már létezik!';
            header('Location: units.php');
            exit;
        }
        
        $existing = $db->fetch("SELECT id FROM units WHERE abbreviation = ? AND is_active = 1", [$abbreviation]);
        if ($existing) {
            $_SESSION['units_error'] = 'Ez a rövidítés már létezik!';
            header('Location: units.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO units (name, abbreviation) VALUES (?, ?)", [$name, $abbreviation]);
            $_SESSION['units_success'] = 'Egység sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['units_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: units.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: units.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['units_error'] = 'Érvénytelen azonosító!';
            header('Location: units.php');
            exit;
        }
        
        try {
            $db->query("UPDATE units SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['units_success'] = 'Egység sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['units_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: units.php');
        exit;
    }
}
?> 