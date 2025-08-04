<?php
require_once __DIR__ . '/../helpers/auth.php';

class ProductCategoryController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Termékkategóriák';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['product_categories_success'])) {
            $success = $_SESSION['product_categories_success'];
            unset($_SESSION['product_categories_success']);
        }
        if (isset($_SESSION['product_categories_error'])) {
            $error = $_SESSION['product_categories_error'];
            unset($_SESSION['product_categories_error']);
        }
        
        // Fetch all categories with parent information
        $categories = $db->fetchAll("
            SELECT 
                pc.*,
                parent.name as parent_name
            FROM product_categories pc
            LEFT JOIN product_categories parent ON pc.parent_id = parent.id
            WHERE pc.is_active = 1 
            ORDER BY pc.level, pc.name
        ");
        
        // Separate main categories and sub-categories
        $mainCategories = [];
        $subCategories = [];
        
        foreach ($categories as $category) {
            if ($category['level'] == 1) {
                $mainCategories[] = $category;
            } else {
                $subCategories[] = $category;
            }
        }
        
        // Get main categories for dropdown
        $mainCategoriesForDropdown = $db->fetchAll("
            SELECT id, name 
            FROM product_categories 
            WHERE level = 1 AND is_active = 1 
            ORDER BY name
        ");
        
        ob_start();
        include __DIR__ . '/../views/product-categories/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: product-categories.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parent_id = $_POST['parent_id'] ?? null;
        $level = $parent_id ? 2 : 1;
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['product_categories_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: product-categories.php');
            exit;
        }
        
        // Check for duplicates within the same parent
        $existing = $db->fetch("SELECT id FROM product_categories WHERE name = ? AND parent_id = ? AND is_active = 1", [$name, $parent_id]);
        if ($existing) {
            $_SESSION['product_categories_error'] = 'Ez a megnevezés már létezik ebben a kategóriában!';
            header('Location: product-categories.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO product_categories (name, description, parent_id, level) VALUES (?, ?, ?, ?)", 
                       [$name, $description, $parent_id, $level]);
            $_SESSION['product_categories_success'] = 'Kategória sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['product_categories_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: product-categories.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: product-categories.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['product_categories_error'] = 'Érvénytelen azonosító!';
            header('Location: product-categories.php');
            exit;
        }
        
        try {
            // Check if this category has sub-categories
            $hasSubCategories = $db->fetch("SELECT id FROM product_categories WHERE parent_id = ? AND is_active = 1", [$id]);
            
            if ($hasSubCategories) {
                $_SESSION['product_categories_error'] = 'Nem lehet törölni a kategóriát, mert tartalmaz alkategóriákat!';
                header('Location: product-categories.php');
                exit;
            }
            
            // Check if this category is used by products (you might want to add this check later)
            // $hasProducts = $db->fetch("SELECT id FROM products WHERE category_id = ? AND is_active = 1", [$id]);
            
            $db->query("UPDATE product_categories SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['product_categories_success'] = 'Kategória sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['product_categories_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: product-categories.php');
        exit;
    }
}
?> 