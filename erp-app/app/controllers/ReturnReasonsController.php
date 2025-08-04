<?php
require_once __DIR__ . '/../helpers/auth.php';

class ReturnReasonsController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Visszaküldési okok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['return_reasons_success'])) {
            $success = $_SESSION['return_reasons_success'];
            unset($_SESSION['return_reasons_success']);
        }
        if (isset($_SESSION['return_reasons_error'])) {
            $error = $_SESSION['return_reasons_error'];
            unset($_SESSION['return_reasons_error']);
        }
        
        // Fetch all return reasons
        $returnReasons = $db->fetchAll("SELECT * FROM return_reasons ORDER BY name");
        
        ob_start();
        include '../app/views/return-reasons/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: return-reasons.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $is_selectable = isset($_POST['is_selectable']) ? 1 : 0;
        $is_creditable = isset($_POST['is_creditable']) ? 1 : 0;
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['return_reasons_error'] = 'A név mező kitöltése kötelező!';
            header('Location: return-reasons.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO return_reasons (name, is_selectable, is_creditable) VALUES (?, ?, ?)", 
                [$name, $is_selectable, $is_creditable]);
            $_SESSION['return_reasons_success'] = 'Visszaküldési ok sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['return_reasons_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: return-reasons.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: return-reasons.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['return_reasons_error'] = 'Érvénytelen azonosító!';
            header('Location: return-reasons.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM return_reasons WHERE id = ?", [$id]);
            $_SESSION['return_reasons_success'] = 'Visszaküldési ok sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['return_reasons_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: return-reasons.php');
        exit;
    }
}
?> 