<?php
require_once __DIR__ . '/../helpers/auth.php';

/**
 * Sources Controller
 * 
 * Handles sources-related functionality
 */

class SourcesController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Források';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['sources_success'])) {
            $success = $_SESSION['sources_success'];
            unset($_SESSION['sources_success']);
        }
        if (isset($_SESSION['sources_error'])) {
            $error = $_SESSION['sources_error'];
            unset($_SESSION['sources_error']);
        }
        
        // Fetch all sources
        $sources = $db->fetchAll("SELECT * FROM sources ORDER BY name");
        
        ob_start();
        include '../app/views/sources/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: sources.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['sources_error'] = 'A név mező kitöltése kötelező!';
            header('Location: sources.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO sources (name, description) VALUES (?, ?)", 
                [$name, $description]);
            $_SESSION['sources_success'] = 'Forrás sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['sources_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: sources.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: sources.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['sources_error'] = 'Érvénytelen azonosító!';
            header('Location: sources.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM sources WHERE id = ?", [$id]);
            $_SESSION['sources_success'] = 'Forrás sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['sources_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: sources.php');
        exit;
    }
}
?> 