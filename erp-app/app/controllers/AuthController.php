<?php
// Load SaaS configuration first
require_once __DIR__ . '/../../../saas-management/config/app.php';

require_once __DIR__ . '/../../../saas-management/app/helpers/tenant.php';

class AuthController {
    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $azonosito = trim($_POST['azonosito'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            try {
                // Validate tenant identifier
                if (empty($azonosito)) {
                    throw new Exception('Az azonosító megadása kötelező!');
                }
                
                if (!validateTenantIdentifier($azonosito)) {
                    throw new Exception('Érvénytelen azonosító formátum!');
                }
                
                // Check if tenant exists
                if (!tenantDatabaseExists($azonosito)) {
                    throw new Exception('A megadott azonosítóval nem található aktív fiók!');
                }
                
                // Get tenant database
                $tenantDb = getTenantDatabase($azonosito);
                
                // Check user credentials
                $user = $tenantDb->fetch("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1", [$username, $username]);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Update last login timestamp
                    $tenantDb->query("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$user['id']]);
                    
                    // Set session data
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['tenant_id'] = $azonosito;
                    
                    // Redirect to dashboard
                    header('Location: ' . ERP_BASE_URL . '/dashboard.php');
                    exit;
                } else {
                    $error = 'Hibás felhasználónév vagy jelszó!';
                }
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Render standalone login page
        include '../app/views/auth/login.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . ERP_BASE_URL . '/login.php');
        exit;
    }
} 