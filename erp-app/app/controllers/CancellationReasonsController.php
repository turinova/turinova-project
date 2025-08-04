<?php
require_once __DIR__ . '/../helpers/auth.php';

class CancellationReasonsController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Törlési okok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['cancellation_reasons_success'])) {
            $success = $_SESSION['cancellation_reasons_success'];
            unset($_SESSION['cancellation_reasons_success']);
        }
        if (isset($_SESSION['cancellation_reasons_error'])) {
            $error = $_SESSION['cancellation_reasons_error'];
            unset($_SESSION['cancellation_reasons_error']);
        }
        
        // Fetch all cancellation reasons
        $cancellationReasons = $db->fetchAll("SELECT * FROM cancellation_reasons ORDER BY text");
        
        ob_start();
        include '../app/views/cancellation-reasons/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cancellation-reasons.php');
            exit;
        }
        
        $text = trim($_POST['text'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Basic validation
        if (empty($text)) {
            $_SESSION['cancellation_reasons_error'] = 'A szöveg mező kitöltése kötelező!';
            header('Location: cancellation-reasons.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO cancellation_reasons (text, description) VALUES (?, ?)", 
                [$text, $description]);
            $_SESSION['cancellation_reasons_success'] = 'Törlési ok sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['cancellation_reasons_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: cancellation-reasons.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cancellation-reasons.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['cancellation_reasons_error'] = 'Érvénytelen azonosító!';
            header('Location: cancellation-reasons.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM cancellation_reasons WHERE id = ?", [$id]);
            $_SESSION['cancellation_reasons_success'] = 'Törlési ok sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['cancellation_reasons_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: cancellation-reasons.php');
        exit;
    }
}
?> 