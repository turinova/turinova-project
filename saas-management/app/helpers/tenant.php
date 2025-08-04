<?php
/**
 * Tenant Management Helper Functions for SaaS
 */

// Load database connection
require_once __DIR__ . '/../../database/connection.php';

/**
 * Get current tenant ID from session
 * @return string|null
 */
function getCurrentTenantId() {
    return $_SESSION['tenant_id'] ?? null;
}

/**
 * Set current tenant ID in session
 * @param string $tenantId
 */
function setCurrentTenantId($tenantId) {
    $_SESSION['tenant_id'] = $tenantId;
}

/**
 * Get tenant database instance
 * @param string $tenantId
 * @return MultiTenantDatabase
 */
function getTenantDatabase($tenantId = null) {
    if (!$tenantId) {
        $tenantId = getCurrentTenantId();
    }
    
    if (!$tenantId) {
        throw new Exception('No tenant ID provided or found in session');
    }
    
    if ($tenantId === "turinova_erp") {
        return MultiTenantDatabase::getInstance(null, "turinova_erp");
    }
    return MultiTenantDatabase::getInstance($tenantId);
}

/**
 * Get master database instance
 * @return MasterDatabase
 */
function getMasterDatabase() {
    return MasterDatabase::getInstance();
}

/**
 * Validate tenant identifier
 * @param string $azonosito
 * @return bool
 */
function validateTenantIdentifier($azonosito) {
    // Basic validation - alphanumeric and hyphens only
    return preg_match("/^[a-zA-Z0-9\-_]+$/", $azonosito);
}

/**
 * Check if tenant database exists
 * @param string $tenantId
 * @return bool
 */
function tenantDatabaseExists($tenantId) {
    try {
        $masterDb = getMasterDatabase();
        $tenant = $masterDb->fetch("SELECT * FROM tenants WHERE identifier = ? AND status = 'active'", [$tenantId]);
        return $tenant ? true : false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get tenant information
 * @param string $tenantId
 * @return array|null
 */
function getTenantInfo($tenantId) {
    try {
        $masterDb = getMasterDatabase();
        return $masterDb->fetch("SELECT * FROM tenants WHERE identifier = ? AND status = 'active'", [$tenantId]);
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Create tenant database
 * @param string $tenantId
 * @param array $tenantData
 * @return bool
 */
function createTenantDatabase($tenantId, $tenantData, $superuserPassword) {
    try {
        $masterDb = getMasterDatabase();
        
        // Insert tenant record
        $masterDb->query("INSERT INTO tenants (identifier, name, email, status, created_at) VALUES (?, ?, ?, 'active', NOW())", 
            [$tenantId, $tenantData['name'], $tenantData['email']]);
        
        // Create tenant database
        $dbName = SAAS_TENANT_DB_PREFIX . $tenantId . SAAS_TENANT_DB_SUFFIX;
        $masterDb->query("CREATE DATABASE IF NOT EXISTS `$dbName`");
        
        // Initialize tenant database with schema
        $tenantDb = MultiTenantDatabase::getInstance($tenantId);
        initializeTenantDatabase($tenantDb, $tenantData, $superuserPassword);
        
        return true;
    } catch (Exception $e) {
        error_log("Failed to create tenant database: " . $e->getMessage());
        return false;
    }
}

/**
 * Initialize tenant database by copying structure and data from template database
 * @param MultiTenantDatabase $db
 */
function initializeTenantDatabase($db, $tenantData = [], $superuserPassword = null) {
    try {
        // Connect to the source database (turinova_sample_erp)
        $sourceDb = new PDO(
            "mysql:host=" . SAAS_DB_HOST . ";unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=" . SAAS_DEFAULT_DB_NAME . ";charset=" . SAAS_DB_CHARSET,
            SAAS_DB_USER,
            SAAS_DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "📋 Copying database structure and data from turinova_sample_erp...\n";
        
        // Get all tables from source database
        $tables = $sourceDb->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        // Define table creation order to handle foreign key constraints
        $tableOrder = [
            'users',           // Base table
            'pages',           // Referenced by user_permissions
            'user_permissions', // References users and pages
            'vat',             // Referenced by fee_types
            'fee_types',       // References vat
            'payment_methods', // No dependencies
            'currencies',      // No dependencies
            'shipping_methods', // No dependencies
            'return_reasons',  // No dependencies
            'sources',         // No dependencies
            'cancellation_reasons', // No dependencies
            'units',           // No dependencies
            'manufacturers',   // No dependencies
            'product_categories', // Self-referencing (parent_id)
            'warehouses',      // Referenced by warehouse_sections
            'warehouse_sections', // Referenced by warehouse_columns
            'warehouse_columns', // Referenced by warehouse_shelves
            'warehouse_shelves', // No dependencies
            'company_data'     // No dependencies
        ];
        
        // Add any other tables that aren't in the predefined order
        foreach ($tables as $table) {
            if (!in_array($table, $tableOrder)) {
                $tableOrder[] = $table;
            }
        }
        
        foreach ($tableOrder as $table) {
            if (!in_array($table, $tables)) continue; // Skip if table doesn't exist in source
            
            echo "  📦 Copying table: $table\n";
            
            // Get table structure
            $createTable = $sourceDb->query("SHOW CREATE TABLE `$table`")->fetch();
            $createStatement = $createTable[1];
            
            // Create table in tenant database
            $db->query($createStatement);
        
            // Copy data (excluding sensitive data like users and company_data)
            if ($table !== 'users' && $table !== 'company_data') {
                $data = $sourceDb->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    $columns = array_keys($data[0]);
                    $placeholders = str_repeat('?,', count($columns) - 1) . '?';
                    $columnList = '`' . implode('`, `', $columns) . '`';
                    
                    // Temporarily disable foreign key checks for data insertion
                    $db->query("SET FOREIGN_KEY_CHECKS = 0");
                    
                    foreach ($data as $row) {
                        $db->query("INSERT INTO `$table` ($columnList) VALUES ($placeholders)", array_values($row));
                    }
                    
                    // Re-enable foreign key checks
                    $db->query("SET FOREIGN_KEY_CHECKS = 1");
                    
                    echo "    ✅ Copied " . count($data) . " records\n";
                }
            } else {
                echo "    ⏭️  Skipped data copy for $table\n";
            }
        }
        
        // Create tenant-specific superuser
        if ($superuserPassword && !empty($tenantData['email'])) {
            $db->query("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, 'superuser')", [
                'superuser',
                $tenantData['email'],
                $superuserPassword,
                'Super',
                'User'
            ]);
        } else {
            $defaultPassword = password_hash('superuser123', PASSWORD_DEFAULT);
            $db->query("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)", ['superuser', 'superuser@turinova.com', $defaultPassword, 'Super', 'User', 'superuser']);
        }
        
        // Give superuser access to all pages (using only existing columns)
        $superuserId = $db->fetch("SELECT id FROM users WHERE username = 'superuser'")['id'];
        $pages = $db->fetchAll("SELECT id FROM pages");
        foreach ($pages as $page) {
            $db->query("INSERT IGNORE INTO user_permissions (user_id, page_id, can_access) VALUES (?, ?, TRUE)", [$superuserId, $page['id']]);
        }
        
        echo "✅ Tenant database initialized successfully!\n";
        echo "🔑 Superuser: superuser@turinova.com / superuser123\n";
        
    } catch (Exception $e) {
        echo "❌ Error initializing tenant database: " . $e->getMessage() . "\n";
        throw $e;
    }
}

/**
 * Require tenant authentication
 */
function requireTenantAuth() {
    if (!getCurrentTenantId()) {
        header('Location: <?= SAAS_BASE_URL ?>/login.php');
        exit;
    }
}
?> 