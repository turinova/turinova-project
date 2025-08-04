<?php
require_once __DIR__ . '/../helpers/auth.php';

class ShelvesController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Polchelyek';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['shelves_success'])) {
            $success = $_SESSION['shelves_success'];
            unset($_SESSION['shelves_success']);
        }
        if (isset($_SESSION['shelves_error'])) {
            $error = $_SESSION['shelves_error'];
            unset($_SESSION['shelves_error']);
        }
        
        // Fetch warehouses first
        $warehouses = $db->fetchAll("SELECT id, name FROM warehouses WHERE is_active = 1 ORDER BY name");
        
        // Organize data hierarchically
        $organizedData = [];
        
        foreach ($warehouses as $warehouse) {
            $warehouseId = $warehouse['id'];
            
            // Initialize warehouse
            $organizedData[$warehouseId] = [
                'id' => $warehouseId,
                'name' => $warehouse['name'],
                'sections' => []
            ];
            
            // Fetch sections for this warehouse
            $sections = $db->fetchAll("SELECT id, name FROM warehouse_sections WHERE warehouse_id = ? AND is_active = 1 ORDER BY name", [$warehouseId]);
            
            foreach ($sections as $section) {
                $sectionId = $section['id'];
                
                // Initialize section
                $organizedData[$warehouseId]['sections'][$sectionId] = [
                    'id' => $sectionId,
                    'name' => $section['name'],
                    'columns' => []
                ];
                
                // Fetch columns for this section
                $columns = $db->fetchAll("SELECT id, name FROM warehouse_columns WHERE section_id = ? AND is_active = 1 ORDER BY name", [$sectionId]);
                
                foreach ($columns as $column) {
                    $columnId = $column['id'];
                    
                    // Initialize column
                    $organizedData[$warehouseId]['sections'][$sectionId]['columns'][$columnId] = [
                        'id' => $columnId,
                        'name' => $column['name'],
                        'shelves' => []
                    ];
                    
                    // Fetch shelves for this column
                    $shelves = $db->fetchAll("SELECT id, name FROM warehouse_shelves WHERE column_id = ? AND is_active = 1 ORDER BY name", [$columnId]);
                    
                    foreach ($shelves as $shelf) {
                        $shelfId = $shelf['id'];
                        
                        // Add shelf
                        $organizedData[$warehouseId]['sections'][$sectionId]['columns'][$columnId]['shelves'][$shelfId] = [
                            'id' => $shelfId,
                            'name' => $shelf['name']
                        ];
                    }
                }
            }
        }
        
        ob_start();
        include __DIR__ . '/../views/shelves/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: shelves.php');
            exit;
        }
        
        $type = $_POST['type'] ?? '';
        $warehouse_id = $_POST['warehouse_id'] ?? '';
        $section_id = $_POST['section_id'] ?? '';
        $column_id = $_POST['column_id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['shelves_error'] = 'A név mező kitöltése kötelező!';
            header('Location: shelves.php');
            exit;
        }
        
        if (empty($warehouse_id)) {
            $_SESSION['shelves_error'] = 'Raktár kiválasztása kötelező!';
            header('Location: shelves.php');
            exit;
        }
        
        try {
            switch ($type) {
                case 'section':
                    // Check for duplicate section name in this warehouse
                    $existing = $db->fetch("SELECT id FROM warehouse_sections WHERE warehouse_id = ? AND name = ? AND is_active = 1", [$warehouse_id, $name]);
                    if ($existing) {
                        $_SESSION['shelves_error'] = 'Ez a sor név már létezik ebben a raktárban!';
                        header('Location: shelves.php');
                        exit;
                    }
                    
                    $db->query("INSERT INTO warehouse_sections (warehouse_id, name) VALUES (?, ?)", [$warehouse_id, $name]);
                    $_SESSION['shelves_success'] = 'Sor sikeresen hozzáadva!';
                    break;
                    
                case 'column':
                    if (empty($section_id)) {
                        $_SESSION['shelves_error'] = 'Sor kiválasztása kötelező!';
                        header('Location: shelves.php');
                        exit;
                    }
                    
                    // Check for duplicate column name in this section
                    $existing = $db->fetch("SELECT id FROM warehouse_columns WHERE section_id = ? AND name = ? AND is_active = 1", [$section_id, $name]);
                    if ($existing) {
                        $_SESSION['shelves_error'] = 'Ez az oszlop név már létezik ebben a sorban!';
                        header('Location: shelves.php');
                        exit;
                    }
                    
                    $db->query("INSERT INTO warehouse_columns (section_id, name) VALUES (?, ?)", [$section_id, $name]);
                    $_SESSION['shelves_success'] = 'Oszlop sikeresen hozzáadva!';
                    break;
                    
                case 'shelf':
                    if (empty($column_id)) {
                        $_SESSION['shelves_error'] = 'Oszlop kiválasztása kötelező!';
                        header('Location: shelves.php');
                        exit;
                    }
                    
                    // Check for duplicate shelf name in this column
                    $existing = $db->fetch("SELECT id FROM warehouse_shelves WHERE column_id = ? AND name = ? AND is_active = 1", [$column_id, $name]);
                    if ($existing) {
                        $_SESSION['shelves_error'] = 'Ez a polc név már létezik ebben az oszlopban!';
                        header('Location: shelves.php');
                        exit;
                    }
                    
                    $db->query("INSERT INTO warehouse_shelves (column_id, name) VALUES (?, ?)", [$column_id, $name]);
                    $_SESSION['shelves_success'] = 'Polc sikeresen hozzáadva!';
                    break;
                    
                default:
                    $_SESSION['shelves_error'] = 'Érvénytelen típus!';
                    header('Location: shelves.php');
                    exit;
            }
        } catch (Exception $e) {
            $_SESSION['shelves_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: shelves.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: shelves.php');
            exit;
        }
        
        $type = $_POST['type'] ?? '';
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['shelves_error'] = 'Érvénytelen azonosító!';
            header('Location: shelves.php');
            exit;
        }
        
        try {
            switch ($type) {
                case 'section':
                    $db->query("UPDATE warehouse_sections SET is_active = 0 WHERE id = ?", [$id]);
                    $_SESSION['shelves_success'] = 'Sor sikeresen törölve!';
                    break;
                    
                case 'column':
                    $db->query("UPDATE warehouse_columns SET is_active = 0 WHERE id = ?", [$id]);
                    $_SESSION['shelves_success'] = 'Oszlop sikeresen törölve!';
                    break;
                    
                case 'shelf':
                    $db->query("UPDATE warehouse_shelves SET is_active = 0 WHERE id = ?", [$id]);
                    $_SESSION['shelves_success'] = 'Polc sikeresen törölve!';
                    break;
                    
                default:
                    $_SESSION['shelves_error'] = 'Érvénytelen típus!';
                    break;
            }
        } catch (Exception $e) {
            $_SESSION['shelves_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: shelves.php');
        exit;
    }

    // AJAX endpoints for dynamic dropdowns
    public function getWarehouses() {
        global $db;
        
        header('Content-Type: application/json');
        
        try {
            $warehouses = $db->fetchAll("SELECT id, name FROM warehouses WHERE is_active = 1 ORDER BY name");
            echo json_encode(['success' => true, 'data' => $warehouses]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getSections() {
        global $db;
        
        header('Content-Type: application/json');
        
        try {
            $warehouse_id = $_GET['warehouse_id'] ?? null;
            
            if (!$warehouse_id) {
                echo json_encode(['success' => true, 'data' => []]);
                return;
            }
            
            $sections = $db->fetchAll("SELECT id, name FROM warehouse_sections WHERE warehouse_id = ? AND is_active = 1 ORDER BY name", 
                                     [$warehouse_id]);
            echo json_encode(['success' => true, 'data' => $sections]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getColumns() {
        global $db;
        
        header('Content-Type: application/json');
        
        try {
            $section_id = $_GET['section_id'] ?? null;
            
            if (!$section_id) {
                echo json_encode(['success' => true, 'data' => []]);
                return;
            }
            
            $columns = $db->fetchAll("SELECT id, name FROM warehouse_columns WHERE section_id = ? AND is_active = 1 ORDER BY name", 
                                    [$section_id]);
            echo json_encode(['success' => true, 'data' => $columns]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
?> 