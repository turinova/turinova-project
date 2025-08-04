<?php
/**
 * Dashboard Controller for SaaS Management
 * 
 * Handles tenant management dashboard functionality
 */

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/tenant.php';

class DashboardController {
    
    public function index() {
        // Require authentication
        requireAuth();
        
        // Check if there's a specific action
        $action = $_GET['action'] ?? '';
        
        if ($action === 'add_tenant') {
            // Handle add tenant action
            $this->addTenant();
            return;
        }
        
        if ($action === 'all_tenants') {
            // Handle all tenants action
            $this->allTenants();
            return;
        }
        
        if ($action === 'edit_superuser') {
            // Handle edit superuser action
            $this->editSuperuser();
            return;
        }
        
        try {
            $masterDb = getMasterDatabase();
            
            // Get tenant statistics
            $stats = $this->getTenantStatistics($masterDb);
            
            // Get all tenants
            $tenants = $masterDb->fetchAll("
                SELECT 
                    id, identifier, name, email, status, plan, 
                    max_users, max_storage_mb, created_at, updated_at
                FROM tenants 
                ORDER BY created_at DESC
            ");
            
            // Pass data to view
            $totalTenants = $stats['total'];
            $activeTenants = $stats['active'];
            $suspendedTenants = $stats['suspended'];
            $totalStorage = $stats['total_storage'];
            
            // Set page title
            $title = 'SaaS Management Dashboard';
            
            // Render the dashboard view
            ob_start();
            include '../app/views/dashboard/index.php';
            $content = ob_get_clean();
            
            include '../app/views/layout/base.php';
            
        } catch (Exception $e) {
            // Handle error
            $error = $e->getMessage();
            include '../app/views/error.php';
        }
    }
    
    /**
     * Get tenant statistics
     */
    private function getTenantStatistics($masterDb) {
        // Total tenants
        $total = $masterDb->fetch("SELECT COUNT(*) as count FROM tenants")['count'];
        
        // Active tenants
        $active = $masterDb->fetch("SELECT COUNT(*) as count FROM tenants WHERE status = 'active'")['count'];
        
        // Suspended tenants
        $suspended = $masterDb->fetch("SELECT COUNT(*) as count FROM tenants WHERE status = 'suspended'")['count'];
        
        // Total storage (sum of all tenant storage limits)
        $totalStorage = $masterDb->fetch("SELECT SUM(max_storage_mb) as total FROM tenants")['total'] ?? 0;
        
        return [
            'total' => $total,
            'active' => $active,
            'suspended' => $suspended,
            'total_storage' => $totalStorage . ' MB'
        ];
    }
    
    /**
     * Add new tenant
     */
    public function addTenant() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $identifier = trim($_POST['identifier'] ?? '');
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $plan = $_POST['plan'] ?? 'basic';
                $maxUsers = (int)($_POST['max_users'] ?? 10);
                
                // Validate inputs
                if (empty($identifier) || empty($name) || empty($email) || empty($password)) {
                    throw new Exception('All fields are required!');
                }
                
                if (!validateTenantIdentifier($identifier)) {
                    throw new Exception('Identifier can only contain letters, numbers and hyphens!');
                }
                
                if (strlen($password) < 6) {
                    throw new Exception('Password must be at least 6 characters long!');
                }
                
                // Check if tenant already exists
                if (tenantDatabaseExists($identifier)) {
                    throw new Exception('This identifier is already taken!');
                }
                
                // Create tenant
                $tenantData = [
                    'name' => $name,
                    'email' => $email,
                    'plan' => $plan,
                    'max_users' => $maxUsers
                ];
                
                if (createTenantDatabase($identifier, $tenantData, password_hash($password, PASSWORD_DEFAULT))) {
                    // Redirect with success message
                    header('Location: dashboard.php?action=add_tenant&success=Tenant created successfully!');
                    exit;
                } else {
                    throw new Exception('Error occurred while creating the tenant!');
                }
                
            } catch (Exception $e) {
                // Redirect with error message
                header('Location: dashboard.php?action=add_tenant&error=' . urlencode($e->getMessage()));
                exit;
            }
        }
        
        // If not POST, show the form within dashboard
        $error = $_GET['error'] ?? '';
        $success = $_GET['success'] ?? '';
        
        // Set page title
        $title = 'Add New Tenant - SaaS Management';
        
        // Render the add tenant view
        ob_start();
        include '../app/views/dashboard/add_tenant_content.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
    
    /**
     * Show all tenants
     */
    public function allTenants() {
        // Require authentication
        requireAuth();
        
        try {
            $masterDb = getMasterDatabase();
            
            // Get all tenants
            $tenants = $masterDb->fetchAll("
                SELECT 
                    id, identifier, name, email, status, plan, 
                    max_users, max_storage_mb, created_at, updated_at
                FROM tenants 
                ORDER BY created_at DESC
            ");
            
            // Set page title
            $title = 'All Tenants - SaaS Management';
            
            // Render the all tenants view
            ob_start();
            include '../app/views/dashboard/all_tenants_content.php';
            $content = ob_get_clean();
            
            include '../app/views/layout/base.php';
            
        } catch (Exception $e) {
            // Handle error
            $error = $e->getMessage();
            include '../app/views/error.php';
        }
    }
    
    /**
     * Edit superuser credentials
     */
    public function editSuperuser() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenantId = (int)($_POST['tenant_id'] ?? 0);
                $newEmail = trim($_POST['new_email'] ?? '');
                $newPassword = $_POST['new_password'] ?? '';
                
                // Validate inputs
                if (empty($tenantId)) {
                    throw new Exception('Invalid tenant ID!');
                }
                
                if (empty($newEmail)) {
                    throw new Exception('Email address is required!');
                }
                
                if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email format!');
                }
                
                // Get tenant info from master database
                $masterDb = getMasterDatabase();
                $tenant = $masterDb->fetch("
                    SELECT identifier, name, email 
                    FROM tenants 
                    WHERE id = ?
                ", [$tenantId]);
                
                if (!$tenant) {
                    throw new Exception('Tenant not found!');
                }
                
                // Connect to tenant's database
                $tenantDb = getTenantDatabase($tenant['identifier']);
                
                // Update superuser email
                $result = $tenantDb->query("
                    UPDATE users 
                    SET email = ? 
                    WHERE role = 'superuser'
                ", [$newEmail]);
                
                // Check if any rows were affected
                if ($result->rowCount() === 0) {
                    throw new Exception('No superuser found in tenant database!');
                }
                
                // Update password if provided
                if (!empty($newPassword)) {
                    if (strlen($newPassword) < 6) {
                        throw new Exception('Password must be at least 6 characters long!');
                    }
                    
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $result = $tenantDb->query("
                        UPDATE users 
                        SET password = ? 
                        WHERE role = 'superuser'
                    ", [$hashedPassword]);
                    
                    // Check if any rows were affected
                    if ($result->rowCount() === 0) {
                        throw new Exception('No superuser found in tenant database for password update!');
                    }
                }
                
                // Update tenant email in master database
                $masterDb->query("
                    UPDATE tenants 
                    SET email = ?, updated_at = NOW() 
                    WHERE id = ?
                ", [$newEmail, $tenantId]);
                
                // Redirect with success message
                header('Location: dashboard.php?action=all_tenants&success=Superuser credentials updated successfully!');
                exit;
                
            } catch (Exception $e) {
                // Redirect with error message
                header('Location: dashboard.php?action=all_tenants&error=' . urlencode($e->getMessage()));
                exit;
            }
        }
        
        // If not POST, redirect back to all tenants page
        header('Location: dashboard.php?action=all_tenants');
        exit;
    }

    /**
     * Get tenant permissions
     */
    public function getTenantPermissions() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $tenantId = (int)($_GET['tenant_id'] ?? 0);
            
            if (!$tenantId) {
                throw new Exception('Tenant ID is required!');
            }
            
            // Get tenant info from master database
            $masterDb = getMasterDatabase();
            $tenant = $masterDb->fetch("
                SELECT id, identifier, name, email 
                FROM tenants 
                WHERE id = ?
            ", [$tenantId]);
            
            if (!$tenant) {
                throw new Exception('Tenant not found!');
            }
            
            // Connect to tenant's database
            $tenantDb = getTenantDatabase($tenant['identifier']);
            
            // Get all pages from tenant database
            $pages = $tenantDb->fetchAll("
                SELECT id, name, title, route, icon, menu_order, is_active
                FROM pages 
                ORDER BY menu_order, title
            ");
            
            // Get current permissions for all users in this tenant
            $users = $tenantDb->fetchAll("
                SELECT id, username, email, first_name, last_name, role
                FROM users 
                ORDER BY username
            ");
            
            // Get permissions for each user
            $userPermissions = [];
            foreach ($users as $user) {
                $permissions = $tenantDb->fetchAll("
                    SELECT p.id, p.name, p.title, p.route, p.icon,
                           COALESCE(up.can_access, 0) as can_access
                    FROM pages p
                    LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
                    ORDER BY p.menu_order, p.title
                ", [$user['id']]);
                
                $userPermissions[$user['id']] = [
                    'user' => $user,
                    'permissions' => $permissions
                ];
            }
            
            // Set proper JSON headers
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'tenant' => $tenant, 
                'pages' => $pages,
                'users' => $users,
                'userPermissions' => $userPermissions
            ]);
            
        } catch (Exception $e) {
            // Set proper JSON headers
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update tenant permissions
     */
    public function updateTenantPermissions() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $tenantId = (int)($_POST['tenant_id'] ?? 0);
            $userId = (int)($_POST['user_id'] ?? 0);
            $permissionsJson = $_POST['permissions'] ?? '{}';
            
            // Parse JSON permissions
            $permissions = json_decode($permissionsJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid permissions format: ' . json_last_error_msg());
            }
            
            if (!$tenantId) {
                throw new Exception('Tenant ID is required!');
            }
            
            if (!$userId) {
                throw new Exception('User ID is required!');
            }
            
            // Get tenant info from master database
            $masterDb = getMasterDatabase();
            $tenant = $masterDb->fetch("
                SELECT identifier 
                FROM tenants 
                WHERE id = ?
            ", [$tenantId]);
            
            if (!$tenant) {
                throw new Exception('Tenant not found!');
            }
            
            // Connect to tenant's database
            $tenantDb = getTenantDatabase($tenant['identifier']);
            
            // Check if user exists in tenant database
            $user = $tenantDb->fetch("SELECT id FROM users WHERE id = ?", [$userId]);
            if (!$user) {
                throw new Exception('User not found in tenant database!');
            }
            
            // Clear existing permissions for this user
            $tenantDb->query("DELETE FROM user_permissions WHERE user_id = ?", [$userId]);
            
            // Insert new permissions
            foreach ($permissions as $pageId => $perms) {
                $canAccess = isset($perms['can_access']) ? 1 : 0;
                if ($canAccess) { // Only insert if access is granted
                    $tenantDb->query("
                        INSERT INTO user_permissions (user_id, page_id, can_access) 
                        VALUES (?, ?, ?)
                    ", [$userId, $pageId, $canAccess]);
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Permissions updated successfully!']);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Edit tenant permissions page
     */
    public function editTenantPermissions() {
        // Require authentication
        requireAuth();
        
        $tenantId = (int)($_GET['tenant_id'] ?? 0);
        
        if (!$tenantId) {
            header('Location: dashboard.php?action=all_tenants&error=' . urlencode('Invalid tenant ID!'));
            exit;
        }
        
        // Get tenant info from master database
        $masterDb = getMasterDatabase();
        $tenant = $masterDb->fetch("
            SELECT id, identifier, name, email 
            FROM tenants 
            WHERE id = ?
        ", [$tenantId]);
        
        if (!$tenant) {
            header('Location: dashboard.php?action=all_tenants&error=' . urlencode('Tenant not found!'));
            exit;
        }
        
        // Set page title
        $title = 'Edit Tenant Permissions - SaaS Management';
        
        // Render the edit permissions view
        ob_start();
        include '../app/views/dashboard/edit_permissions_content.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
    
    /**
     * Update tenant status
     */
    public function updateTenantStatus() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenantId = (int)($_POST['tenant_id'] ?? 0);
                $status = $_POST['status'] ?? '';
                
                if (!$tenantId || !in_array($status, ['active', 'inactive', 'suspended'])) {
                    throw new Exception('Invalid parameters');
                }
                
                $masterDb = getMasterDatabase();
                $masterDb->query("UPDATE tenants SET status = ? WHERE id = ?", [$status, $tenantId]);
                
                // Redirect with success message
                header('Location: dashboard.php?success=Tenant status updated!');
                exit;
                
            } catch (Exception $e) {
                // Redirect with error message
                header('Location: dashboard.php?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
        
        // If not POST, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    }
    
    /**
     * Delete tenant
     */
    public function deleteTenant() {
        // Require authentication
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenantId = (int)($_POST['tenant_id'] ?? 0);
                
                if (!$tenantId) {
                    throw new Exception('Invalid tenant ID');
                }
                
                $masterDb = getMasterDatabase();
                
                // Get tenant info
                $tenant = $masterDb->fetch("SELECT identifier FROM tenants WHERE id = ?", [$tenantId]);
                if (!$tenant) {
                    throw new Exception('Tenant not found');
                }
                
                // Drop tenant database
                $dbName = SAAS_TENANT_DB_PREFIX . $tenant['identifier'] . SAAS_TENANT_DB_SUFFIX;
                $masterDb->query("DROP DATABASE IF EXISTS `$dbName`");
                
                // Delete tenant record
                $masterDb->query("DELETE FROM tenants WHERE id = ?", [$tenantId]);
                
                // Redirect with success message
                header('Location: dashboard.php?success=Tenant deleted successfully!');
                exit;
                
            } catch (Exception $e) {
                // Redirect with error message
                header('Location: dashboard.php?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
        
        // If not POST, redirect to dashboard
        header('Location: dashboard.php');
        exit;
    }
}
?> 