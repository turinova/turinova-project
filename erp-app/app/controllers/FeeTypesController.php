<?php
require_once __DIR__ . '/../helpers/auth.php';

class FeeTypesController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Díjtípusok';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['fee_types_success'])) {
            $success = $_SESSION['fee_types_success'];
            unset($_SESSION['fee_types_success']);
        }
        if (isset($_SESSION['fee_types_error'])) {
            $error = $_SESSION['fee_types_error'];
            unset($_SESSION['fee_types_error']);
        }
        
        // Fetch all fee types with VAT information
        $feeTypes = $db->fetchAll("
            SELECT ft.*, v.name as vat_name, v.rate as vat_rate 
            FROM fee_types ft 
            LEFT JOIN vat v ON ft.vat_id = v.id 
            ORDER BY ft.name
        ");
        
        ob_start();
        include '../app/views/fee-types/index.php';
        $content = ob_get_clean();
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: fee-types.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $net_price = trim($_POST['net_price'] ?? '');
        $vat_id = trim($_POST['vat_id'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['fee_types_error'] = 'A név mező kitöltése kötelező!';
            header('Location: fee-types.php');
            exit;
        }
        
        if (empty($type)) {
            $_SESSION['fee_types_error'] = 'A típus mező kitöltése kötelező!';
            header('Location: fee-types.php');
            exit;
        }
        
        if (!is_numeric($net_price)) {
            $_SESSION['fee_types_error'] = 'A nettó ár értéke szám kell legyen!';
            header('Location: fee-types.php');
            exit;
        }
        
        if (empty($vat_id)) {
            $_SESSION['fee_types_error'] = 'Az ÁFA kiválasztása kötelező!';
            header('Location: fee-types.php');
            exit;
        }
        
        try {
            // Get VAT rate for calculation
            $vatRate = $db->fetch("SELECT rate FROM vat WHERE id = ?", [$vat_id])['rate'];
            $grossPrice = $net_price * (1 + ($vatRate / 100));
            
            $db->query("INSERT INTO fee_types (name, type, net_price, vat_id, gross_price) VALUES (?, ?, ?, ?, ?)", 
                [$name, $type, $net_price, $vat_id, $grossPrice]);
            $_SESSION['fee_types_success'] = 'Díjtípus sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['fee_types_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: fee-types.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: fee-types.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['fee_types_error'] = 'Érvénytelen azonosító!';
            header('Location: fee-types.php');
            exit;
        }
        
        try {
            $db->query("DELETE FROM fee_types WHERE id = ?", [$id]);
            $_SESSION['fee_types_success'] = 'Díjtípus sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['fee_types_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: fee-types.php');
        exit;
    }

    public function getVatOptions() {
        global $db;
        
        try {
            $vatOptions = $db->fetchAll("SELECT id, name, rate FROM vat ORDER BY name");
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $vatOptions]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function calculateGrossPrice() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }
        
        $net_price = floatval($_POST['net_price'] ?? 0);
        $vat_id = intval($_POST['vat_id'] ?? 0);
        
        try {
            $vatRate = $db->fetch("SELECT rate FROM vat WHERE id = ?", [$vat_id])['rate'];
            $grossPrice = $net_price * (1 + ($vatRate / 100));
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'gross_price' => number_format($grossPrice, 2, '.', ''),
                'vat_rate' => $vatRate
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?> 