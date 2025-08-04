<?php
require_once __DIR__ . '/../helpers/auth.php';

class UserController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        global $db;
        
        // Get all users with their permissions
        $users = $db->fetchAll("
            SELECT u.*, 
                   GROUP_CONCAT(p.name) as accessible_pages
            FROM users u
            LEFT JOIN user_permissions up ON u.id = up.user_id
            LEFT JOIN pages p ON up.page_id = p.id AND up.can_access = 1
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ");
        
        // Set page title
        $title = 'Felhasználók';
        
        // Render the users view
        ob_start();
        include '../app/views/users/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            // Validation
            if (empty($username) || empty($email) || empty($password)) {
                throw new Exception('Minden mező kitöltése kötelező!');
            }
            
            if (strlen($password) < 6) {
                throw new Exception('A jelszónak legalább 6 karakter hosszúnak kell lennie!');
            }
            
            // Check if username or email already exists
            $existingUser = $db->fetch("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
            if ($existingUser) {
                throw new Exception('A felhasználónév vagy email cím már foglalt!');
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $db->query("INSERT INTO users (username, email, password, role, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)",
                [$username, $email, $hashedPassword, $role, $username, '']);
            
            $userId = $db->lastInsertId();
            
            // Grant default access permissions for all pages
            $allPages = $db->fetchAll("SELECT id FROM pages ORDER BY menu_order");
            foreach ($allPages as $page) {
                $db->query("INSERT INTO user_permissions (user_id, page_id, can_access) VALUES (?, ?, 1)",
                    [$userId, $page['id']]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Felhasználó sikeresen létrehozva!']);
            
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getPermissions() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $userId = $_GET['user_id'] ?? null;
            
            if (!$userId) {
                throw new Exception('Felhasználó ID megadása kötelező!');
            }
            
            // Get user info
            $user = $db->fetch("SELECT id, username FROM users WHERE id = ?", [$userId]);
            if (!$user) {
                throw new Exception('Felhasználó nem található!');
            }
            
            // Get all pages dynamically from database
            $permissions = $db->fetchAll("
                SELECT p.id, p.name, p.title, p.route, p.icon,
                       COALESCE(up.can_access, 0) as can_access
                FROM pages p
                LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
                ORDER BY p.menu_order, p.title
            ", [$userId]);
            
            echo json_encode(['success' => true, 'user' => $user, 'permissions' => $permissions]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updatePermissions() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $userId = $_POST['user_id'] ?? null;
            $permissions = $_POST['permissions'] ?? [];
            
            if (!$userId) {
                throw new Exception('Felhasználó ID megadása kötelező!');
            }
            
            // Check if user exists
            $user = $db->fetch("SELECT id FROM users WHERE id = ?", [$userId]);
            if (!$user) {
                throw new Exception('Felhasználó nem található!');
            }
            
            // Clear existing permissions
            $db->query("DELETE FROM user_permissions WHERE user_id = ?", [$userId]);
            
            // Insert new permissions
            foreach ($permissions as $pageId => $perms) {
                $canAccess = isset($perms['can_access']) ? 1 : 0;
                if ($canAccess) { // Only insert if access is granted
                    $db->query("INSERT INTO user_permissions (user_id, page_id, can_access) VALUES (?, ?, ?)",
                        [$userId, $pageId, $canAccess]);
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Jogosultságok sikeresen frissítve!']);
            
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function changePassword() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $userId = $_POST['user_id'] ?? null;
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (!$userId) {
                throw new Exception('Felhasználó ID megadása kötelező!');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Új jelszó megadása kötelező!');
            }
            
            if (strlen($newPassword) < 6) {
                throw new Exception('A jelszónak legalább 6 karakter hosszúnak kell lennie!');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('A jelszók nem egyeznek!');
            }
            
            // Check if user exists
            $user = $db->fetch("SELECT id FROM users WHERE id = ?", [$userId]);
            if (!$user) {
                throw new Exception('Felhasználó nem található!');
            }
            
            // Hash and update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $userId]);
            
            echo json_encode(['success' => true, 'message' => 'Jelszó sikeresen módosítva!']);
            
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $userId = $_POST['user_id'] ?? null;
            
            if (!$userId) {
                throw new Exception('Felhasználó ID megadása kötelező!');
            }
            
            // Get user info
            $user = $db->fetch("SELECT id, username, role FROM users WHERE id = ?", [$userId]);
            if (!$user) {
                throw new Exception('Felhasználó nem található!');
            }
            
            // Prevent deleting superuser
            if ($user['role'] === 'superuser') {
                throw new Exception('Superuser felhasználót nem lehet törölni!');
            }
            
            // Prevent deleting self
            if ($userId == $_SESSION['user_id']) {
                throw new Exception('Saját fiókját nem törölheti!');
            }
            
            // Delete user (permissions will be deleted automatically due to CASCADE)
            $db->query("DELETE FROM users WHERE id = ?", [$userId]);
            
            echo json_encode(['success' => true, 'message' => 'Felhasználó sikeresen törölve!']);
            
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 