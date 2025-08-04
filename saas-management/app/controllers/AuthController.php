<?php
require_once __DIR__ . '/../helpers/tenant.php';

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
                    
                    // Redirect to main ERP dashboard
                    header('Location: <?= ERP_BASE_URL ?>/dashboard.php');
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
        // Clear all session data
        $_SESSION = array();
        
        // Destroy session
        session_destroy();
        
        // Clear session cookies
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }
        
        header('Location: <?= SAAS_BASE_URL ?>/admin_login.php');
        exit;
    }
    
    public function register() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $azonosito = trim($_POST['azonosito'] ?? '');
            $companyName = trim($_POST['company_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            try {
                // Validate inputs
                if (empty($azonosito) || empty($companyName) || empty($email) || empty($password)) {
                    throw new Exception('Minden mező kitöltése kötelező!');
                }
                
                if (!validateTenantIdentifier($azonosito)) {
                    throw new Exception('Az azonosító csak betűket, számokat és kötőjeleket tartalmazhat!');
                }
                
                if (strlen($password) < 6) {
                    throw new Exception('A jelszónak legalább 6 karakter hosszúnak kell lennie!');
                }
                
                // Check if tenant already exists
                if (tenantDatabaseExists($azonosito)) {
                    throw new Exception('Ez az azonosító már foglalt!');
                }
                
                // Create tenant
                $tenantData = [
                    'name' => $companyName,
                    'email' => $email
                ];
                
                if (createTenantDatabase($azonosito, $tenantData, password_hash($password, PASSWORD_DEFAULT))) {
                    $success = 'Fiók sikeresen létrehozva! Most már bejelentkezhet.';
                } else {
                    throw new Exception('Hiba történt a fiók létrehozása során!');
                }
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Render registration page
        include '../app/views/auth/register.php';
    }

    /**
     * SaaS Admin Login (for managing tenants)
     */
    public function adminLogin() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            try {
                // Validate inputs
                if (empty($email) || empty($password)) {
                    throw new Exception('Minden mező kitöltése kötelező!');
                }
                
                // Get master database
                $masterDb = getMasterDatabase();
                
                // Check user credentials in master database
                $user = $masterDb->fetch("SELECT * FROM saas_administrators WHERE email = ? AND status = 'active' LIMIT 1", [$email]);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Update last login timestamp
                    $masterDb->query("UPDATE saas_administrators SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$user['id']]);
                    
                    // Set session data for SaaS admin
                    $_SESSION['saas_user_id'] = $user['id'];
                    $_SESSION['saas_user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['saas_user_role'] = $user['role'];
                    $_SESSION['saas_user_email'] = $user['email'];
                    $_SESSION['is_saas_admin'] = true;
                    
                    // Redirect to SaaS management dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Hibás email vagy jelszó!';
                }
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Render SaaS admin login page
        include '../app/views/auth/admin_login.php';
    }
}
?> 