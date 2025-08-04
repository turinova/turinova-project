# ERP System Function Creation Guide

## Overview
This guide documents the complete process for adding new CRUD functionality to the ERP system, including database setup, controller creation, view implementation, and tenant creation integration.

## Table of Contents
1. [Database Setup](#database-setup)
2. [Controller Creation](#controller-creation)
3. [View Implementation](#view-implementation)
4. [Entry Point Configuration](#entry-point-configuration)
5. [Route Configuration](#route-configuration)
6. [Tenant Creation Integration](#tenant-creation-integration)
7. [Testing Process](#testing-process)
8. [Complete Example: Manufacturers](#complete-example-manufacturers)

---

## Database Setup

### Step 1: Create Migration File
Create a migration file in `erp-app/database/migrations/` with the following structure:

```php
<?php
/**
 * Migration: Create [table_name] table
 * Date: 2024-12-XX
 */

// Load database connection
require_once __DIR__ . '/../connection.php';

try {
    // Create table SQL
    $sql = "
    CREATE TABLE IF NOT EXISTS `[table_name]` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
        `description` varchar(500) DEFAULT NULL COMMENT 'Megjegyzés',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->query($sql);
    echo "✅ [Table Name] table created successfully\n";
    
    // Insert default data
    $defaultData = [
        ['name' => 'Example 1', 'description' => 'Description 1'],
        ['name' => 'Example 2', 'description' => 'Description 2'],
        // Add more default records...
    ];
    
    foreach ($defaultData as $item) {
        $db->query("INSERT IGNORE INTO [table_name] (name, description) VALUES (?, ?)", 
                   [$item['name'], $item['description']]);
    }
    
    echo "✅ Default [table_name] data inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating [table_name] table: " . $e->getMessage() . "\n";
}
?>
```

### Step 2: Create Ensure Script
Create an ensure script in `erp-app/database/ensure_[table_name]_table.php`:

```php
<?php
/**
 * Ensure [table_name] table exists in current tenant database
 */

// Load database connection
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE '[table_name]'");
    
    if (!$tableExists) {
        // Create table
        $sql = "
        CREATE TABLE `[table_name]` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
            `description` varchar(500) DEFAULT NULL COMMENT 'Megjegyzés',
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $db->query($sql);
        echo "✅ [Table Name] table created successfully\n";
        
        // Insert default data
        $defaultData = [
            ['name' => 'Example 1', 'description' => 'Description 1'],
            ['name' => 'Example 2', 'description' => 'Description 2'],
        ];
        
        foreach ($defaultData as $item) {
            $db->query("INSERT INTO [table_name] (name, description) VALUES (?, ?)", 
                       [$item['name'], $item['description']]);
        }
        
        echo "✅ Default [table_name] data inserted successfully\n";
        
    } else {
        // Check if table is empty
        $count = $db->fetch("SELECT COUNT(*) as count FROM [table_name]")['count'];
        
        if ($count == 0) {
            echo "📋 [Table Name] table exists but is empty, inserting default data...\n";
            
            $defaultData = [
                ['name' => 'Example 1', 'description' => 'Description 1'],
                ['name' => 'Example 2', 'description' => 'Description 2'],
            ];
            
            foreach ($defaultData as $item) {
                $db->query("INSERT INTO [table_name] (name, description) VALUES (?, ?)", 
                           [$item['name'], $item['description']]);
            }
            
            echo "✅ Default [table_name] data inserted successfully\n";
        } else {
            echo "✅ [Table Name] table already exists with data ($count records)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error ensuring [table_name] table: " . $e->getMessage() . "\n";
}
?>
```

### Step 3: Run Migration
Execute the migration to create the table in the sample database:

```bash
php erp-app/database/migrations/2024_12_add_[table_name]_table.php
```

---

## Controller Creation

### Step 1: Create Controller File
Create `erp-app/app/controllers/[TableName]Controller.php`:

```php
<?php
require_once __DIR__ . '/../helpers/auth.php';

class [TableName]Controller {
    public function __construct() {}

    public function index() {
        global $db;
        $title = '[Page Title]';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['[table_name]_success'])) {
            $success = $_SESSION['[table_name]_success'];
            unset($_SESSION['[table_name]_success']);
        }
        if (isset($_SESSION['[table_name]_error'])) {
            $error = $_SESSION['[table_name]_error'];
            unset($_SESSION['[table_name]_error']);
        }
        
        // Fetch all records
        $[table_name] = $db->fetchAll("SELECT * FROM [table_name] WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include __DIR__ . '/../views/[table_name]/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: [table_name].php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Basic validation
        if (empty($name)) {
            $_SESSION['[table_name]_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: [table_name].php');
            exit;
        }
        
        // Check for duplicates
        $existing = $db->fetch("SELECT id FROM [table_name] WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['[table_name]_error'] = 'Ez a megnevezés már létezik!';
            header('Location: [table_name].php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO [table_name] (name, description) VALUES (?, ?)", [$name, $description]);
            $_SESSION['[table_name]_success'] = '[Item Name] sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['[table_name]_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: [table_name].php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: [table_name].php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['[table_name]_error'] = 'Érvénytelen azonosító!';
            header('Location: [table_name].php');
            exit;
        }
        
        try {
            $db->query("UPDATE [table_name] SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['[table_name]_success'] = '[Item Name] sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['[table_name]_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: [table_name].php');
        exit;
    }
}
?>
```

---

## View Implementation

### Step 1: Create View Directory
Create directory: `erp-app/app/views/[table_name]/`

### Step 2: Create View File
Create `erp-app/app/views/[table_name]/index.php`:

```php
<?php
// Ensure we have the data
if (!isset($[table_name])) {
    $[table_name] = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">[Page Title]</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add[TableName]Modal">
            <i class="ri ri-add-line me-2"></i>Új [item name]
        </button>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="ri ri-check-line me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="ri ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">[Page Title] listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($[table_name])): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-[icon]-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek [item name]</p>
                        <small>Kattints az "Új [item name]" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Megjegyzés</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($[table_name] as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($item['description'] ?? '') ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="delete[TableName](<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>')">
                                            <i class="ri ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add[TableName]Modal" tabindex="-1" aria-labelledby="add[TableName]ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add[TableName]ModalLabel">Új [item name] hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="[table_name].php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="pl. [Example]">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Megjegyzés</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Opcionális megjegyzés..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-save-line me-2"></i>Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete[TableName]Modal" tabindex="-1" aria-labelledby="delete[TableName]ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete[TableName]ModalLabel">[Item Name] törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="delete[TableName]Name"></span>" [item name]?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="[table_name].php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="delete[TableName]Id">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function delete[TableName](id, name) {
    document.getElementById('delete[TableName]Id').value = id;
    document.getElementById('delete[TableName]Name').textContent = name;
    new bootstrap.Modal(document.getElementById('delete[TableName]Modal')).show();
}
</script>
```

---

## Entry Point Configuration

### Step 1: Create Entry Point
Create `erp-app/public/[table_name].php`:

```php
<?php
/**
 * [Table Name] Entry Point
 */

// Load configuration first
require_once '../config/app.php';
require_once '../config/session.php';

// Start session after configuration
session_start();

// Load database connection
require_once '../database/connection.php';

// Load helpers
require_once '../app/helpers/validation.php';
require_once '../app/helpers/flash.php';
require_once '../app/helpers/auth.php';

// Require authentication and page access
requirePageAccess('[table_name]');

// Check if this is an add or delete action
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call add method
        require_once '../app/controllers/[TableName]Controller.php';
        $controller = new [TableName]Controller();
        $controller->add();
        exit;
    }
    
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Load the controller and call delete method
        require_once '../app/controllers/[TableName]Controller.php';
        $controller = new [TableName]Controller();
        $controller->delete();
        exit;
    }
}

// Set path for routing
$path = '/[table_name]';

// Load routes
require_once '../routes/web.php';
?>
```

---

## Route Configuration

### Step 1: Add Route
Add the route to `erp-app/routes/web.php`:

```php
'/[table_name]' => ['[TableName]Controller', 'index'],
```

**Important:** Ensure the controller name matches exactly (case-sensitive).

---

## Tenant Creation Integration

### Step 1: Update Tenant Helper
Add the table to the creation order in `saas-management/app/helpers/tenant.php`:

```php
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
    '[table_name]',    // No dependencies - ADD THIS LINE
    'company_data'     // No dependencies
];
```

### Step 2: Verify Table in Sample Database
Ensure the table exists in `turinova_sample_erp` database:

```bash
php -r "require_once 'erp-app/database/connection.php'; \$tables = \$db->fetchAll('SHOW TABLES'); foreach (\$tables as \$table) { echo array_values(\$table)[0] . PHP_EOL; }"
```

---

## Testing Process

### Step 1: Test Database Operations
Create a test script to verify CRUD operations:

```php
<?php
/**
 * Test [Table Name] Functionality
 */

require_once 'erp-app/config/app.php';
require_once 'erp-app/database/connection.php';

echo "🧪 Testing [Table Name] Functionality\n";
echo "=====================================\n\n";

try {
    // Test 1: Check if table has data
    echo "1. Checking [table_name] data...\n";
    $items = $db->fetchAll("SELECT * FROM [table_name] WHERE is_active = 1 ORDER BY name");
    echo "   ✅ Found " . count($items) . " items\n";
    
    // Test 2: Test adding a new item
    echo "\n2. Testing [table_name] addition...\n";
    $testItem = [
        'name' => 'Test Item ' . time(),
        'description' => 'Test Description'
    ];
    
    $db->query("INSERT INTO [table_name] (name, description) VALUES (?, ?)", 
               [$testItem['name'], $testItem['description']]);
    echo "   ✅ Test item added successfully\n";
    
    // Test 3: Test soft delete
    echo "\n3. Testing [table_name] deletion...\n";
    $db->query("UPDATE [table_name] SET is_active = 0 WHERE name = ?", [$testItem['name']]);
    echo "   ✅ Test item soft deleted\n";
    
    // Test 4: Check for duplicate constraints
    echo "\n4. Testing duplicate constraints...\n";
    try {
        $db->query("INSERT INTO [table_name] (name, description) VALUES (?, ?)", 
                   ['Existing Name', 'test_dup']);
        echo "   ❌ Should have failed - duplicate name\n";
    } catch (Exception $e) {
        echo "   ✅ Duplicate name constraint working\n";
    }
    
    echo "\n✅ All [table_name] functionality tests passed!\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}
?>
```

### Step 2: Test Tenant Creation
Create a test script to verify tenant creation includes the new table:

```php
<?php
/**
 * Test Tenant Creation with [Table Name] Table
 */

require_once 'saas-management/config/app.php';
require_once 'saas-management/app/helpers/tenant.php';

echo "🧪 Testing Tenant Creation with [Table Name] Table\n";
echo "==================================================\n\n";

try {
    // Test tenant data
    $testTenantId = 'test_[table_name]_' . time();
    $tenantData = [
        'name' => 'Test [Table Name] Tenant',
        'email' => 'test@example.com'
    ];
    $superuserPassword = password_hash('test123', PASSWORD_DEFAULT);
    
    echo "📝 Creating test tenant: $testTenantId\n";
    
    // Create tenant
    $result = createTenantDatabase($testTenantId, $tenantData, $superuserPassword);
    
    if ($result) {
        echo "✅ Tenant creation successful!\n\n";
        
        // Verify the tenant database was created with the new table
        $tenantDb = getTenantDatabase($testTenantId);
        $items = $tenantDb->fetchAll("SELECT * FROM [table_name] WHERE is_active = 1");
        
        echo "📋 [Table Name] in new tenant database:\n";
        echo "   - Found " . count($items) . " items\n";
        
        if (count($items) > 0) {
            echo "   - Sample items:\n";
            foreach (array_slice($items, 0, 3) as $item) {
                echo "     * {$item['name']} ({$item['description']})\n";
            }
        }
        
        // Clean up - delete the test tenant
        $masterDb = getMasterDatabase();
        $masterDb->query("DELETE FROM tenants WHERE identifier = ?", [$testTenantId]);
        $masterDb->query("DROP DATABASE IF EXISTS `turinova_{$testTenantId}_erp`");
        
        echo "\n🧹 Test tenant cleaned up\n";
        echo "\n✅ [Table Name] table is properly included in tenant creation!\n";
        
    } else {
        echo "❌ Tenant creation failed!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
```

---

## Complete Example: Manufacturers

### Database Migration
**File:** `erp-app/database/migrations/2024_12_add_manufacturers_table.php`

```php
<?php
/**
 * Migration: Create manufacturers table
 * Date: 2024-12-XX
 */

require_once __DIR__ . '/../connection.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `manufacturers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL COMMENT 'Megnevezés',
        `country` varchar(100) NOT NULL COMMENT 'Ország',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->query($sql);
    echo "✅ Manufacturers table created successfully\n";
    
    $defaultManufacturers = [
        ['name' => 'Samsung', 'country' => 'Dél-Korea'],
        ['name' => 'Apple', 'country' => 'USA'],
        ['name' => 'Huawei', 'country' => 'Kína'],
        ['name' => 'Xiaomi', 'country' => 'Kína'],
        ['name' => 'LG', 'country' => 'Dél-Korea'],
        ['name' => 'Sony', 'country' => 'Japán'],
        ['name' => 'Panasonic', 'country' => 'Japán'],
        ['name' => 'Philips', 'country' => 'Hollandia'],
        ['name' => 'Bosch', 'country' => 'Németország'],
        ['name' => 'Siemens', 'country' => 'Németország']
    ];
    
    foreach ($defaultManufacturers as $manufacturer) {
        $db->query("INSERT IGNORE INTO manufacturers (name, country) VALUES (?, ?)", 
                   [$manufacturer['name'], $manufacturer['country']]);
    }
    
    echo "✅ Default manufacturers inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating manufacturers table: " . $e->getMessage() . "\n";
}
?>
```

### Controller
**File:** `erp-app/app/controllers/ManufacturersController.php`

```php
<?php
require_once __DIR__ . '/../helpers/auth.php';

class ManufacturersController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Gyártók';
        $error = '';
        $success = '';
        
        if (isset($_SESSION['manufacturers_success'])) {
            $success = $_SESSION['manufacturers_success'];
            unset($_SESSION['manufacturers_success']);
        }
        if (isset($_SESSION['manufacturers_error'])) {
            $error = $_SESSION['manufacturers_error'];
            unset($_SESSION['manufacturers_error']);
        }
        
        $manufacturers = $db->fetchAll("SELECT * FROM manufacturers WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include __DIR__ . '/../views/manufacturers/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manufacturers.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        
        if (empty($name)) {
            $_SESSION['manufacturers_error'] = 'A megnevezés mező kitöltése kötelező!';
            header('Location: manufacturers.php');
            exit;
        }
        
        if (empty($country)) {
            $_SESSION['manufacturers_error'] = 'Az ország mező kitöltése kötelező!';
            header('Location: manufacturers.php');
            exit;
        }
        
        $existing = $db->fetch("SELECT id FROM manufacturers WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['manufacturers_error'] = 'Ez a megnevezés már létezik!';
            header('Location: manufacturers.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO manufacturers (name, country) VALUES (?, ?)", [$name, $country]);
            $_SESSION['manufacturers_success'] = 'Gyártó sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['manufacturers_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: manufacturers.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manufacturers.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['manufacturers_error'] = 'Érvénytelen azonosító!';
            header('Location: manufacturers.php');
            exit;
        }
        
        try {
            $db->query("UPDATE manufacturers SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['manufacturers_success'] = 'Gyártó sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['manufacturers_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: manufacturers.php');
        exit;
    }
}
?>
```

### View
**File:** `erp-app/app/views/manufacturers/index.php`

```php
<?php
if (!isset($manufacturers)) {
    $manufacturers = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Gyártók</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addManufacturerModal">
            <i class="ri ri-add-line me-2"></i>Új gyártó
        </button>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="ri ri-check-line me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="ri ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Gyártók listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($manufacturers)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-building-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek gyártók</p>
                        <small>Kattints az "Új gyártó" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Ország</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($manufacturer['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info"><?= htmlspecialchars($manufacturer['country']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteManufacturer(<?= $manufacturer['id'] ?>, '<?= htmlspecialchars($manufacturer['name']) ?>')">
                                            <i class="ri ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Manufacturer Modal -->
<div class="modal fade" id="addManufacturerModal" tabindex="-1" aria-labelledby="addManufacturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManufacturerModalLabel">Új gyártó hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="manufacturers.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="pl. Samsung">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Ország *</label>
                        <input type="text" class="form-control" id="country" name="country" required 
                               placeholder="pl. Dél-Korea">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-save-line me-2"></i>Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteManufacturerModal" tabindex="-1" aria-labelledby="deleteManufacturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteManufacturerModalLabel">Gyártó törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteManufacturerName"></span>" gyártót?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="manufacturers.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteManufacturerId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteManufacturer(id, name) {
    document.getElementById('deleteManufacturerId').value = id;
    document.getElementById('deleteManufacturerName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteManufacturerModal')).show();
}
</script>
```

### Entry Point
**File:** `erp-app/public/manufacturers.php`

```php
<?php
/**
 * Manufacturers Entry Point
 */

require_once '../config/app.php';
require_once '../config/session.php';

session_start();

require_once '../database/connection.php';
require_once '../app/helpers/validation.php';
require_once '../app/helpers/flash.php';
require_once '../app/helpers/auth.php';

requirePageAccess('manufacturers');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once '../app/controllers/ManufacturersController.php';
        $controller = new ManufacturersController();
        $controller->add();
        exit;
    }
    
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once '../app/controllers/ManufacturersController.php';
        $controller = new ManufacturersController();
        $controller->delete();
        exit;
    }
}

$path = '/manufacturers';
require_once '../routes/web.php';
?>
```

### Route Configuration
**File:** `erp-app/routes/web.php` (add this line):

```php
'/manufacturers' => ['ManufacturersController', 'index'],
```

### Tenant Integration
**File:** `saas-management/app/helpers/tenant.php` (add to table order):

```php
'manufacturers',   // No dependencies
```

---

## Complete Example: Warehouses

### Database Setup
**Migration File:** `erp-app/database/migrations/2024_12_add_warehouses_table.php`

```php
<?php
/**
 * Migration: Create warehouses table
 * Date: 2024-12-XX
 */

require_once __DIR__ . '/../connection.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `warehouses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL COMMENT 'Név',
        `country` varchar(100) NOT NULL COMMENT 'Ország',
        `postal_code` varchar(20) NOT NULL COMMENT 'Irányítószám',
        `city` varchar(100) NOT NULL COMMENT 'Város',
        `address` varchar(255) NOT NULL COMMENT 'Utca, Házszám',
        `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'Státusz',
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->query($sql);
    echo "✅ Warehouses table created successfully\n";
    
    // Insert default data
    $defaultData = [
        [
            'name' => 'Fő Raktár',
            'country' => 'Magyarország',
            'postal_code' => '1111',
            'city' => 'Budapest',
            'address' => 'Kossuth utca 1.',
            'status' => 'active'
        ],
        [
            'name' => 'Debreceni Raktár',
            'country' => 'Magyarország',
            'postal_code' => '4000',
            'city' => 'Debrecen',
            'address' => 'Piac utca 15.',
            'status' => 'active'
        ],
        [
            'name' => 'Szegedi Raktár',
            'country' => 'Magyarország',
            'postal_code' => '6720',
            'city' => 'Szeged',
            'address' => 'Dugonics tér 13.',
            'status' => 'active'
        ]
    ];
    
    foreach ($defaultData as $item) {
        $db->query("INSERT IGNORE INTO warehouses (name, country, postal_code, city, address, status) VALUES (?, ?, ?, ?, ?, ?)", 
                   [$item['name'], $item['country'], $item['postal_code'], $item['city'], $item['address'], $item['status']]);
    }
    
    echo "✅ Default warehouses data inserted successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error creating warehouses table: " . $e->getMessage() . "\n";
}
?>
```

**Ensure Script:** `erp-app/database/ensure_warehouses_table.php`

```php
<?php
/**
 * Ensure warehouses table exists in current tenant database
 */

require_once __DIR__ . '/connection.php';

try {
    $tableExists = $db->fetch("SHOW TABLES LIKE 'warehouses'");
    
    if (!$tableExists) {
        // Create table (same SQL as migration)
        $sql = "CREATE TABLE `warehouses` (/* table structure */)";
        $db->query($sql);
        
        // Insert default data
        $defaultData = [/* same data as migration */];
        foreach ($defaultData as $item) {
            $db->query("INSERT INTO warehouses (name, country, postal_code, city, address, status) VALUES (?, ?, ?, ?, ?, ?)", 
                       [$item['name'], $item['country'], $item['postal_code'], $item['city'], $item['address'], $item['status']]);
        }
    } else {
        // Check if empty and populate if needed
        $count = $db->fetch("SELECT COUNT(*) as count FROM warehouses")['count'];
        if ($count == 0) {
            // Insert default data
        }
    }
} catch (Exception $e) {
    echo "❌ Error ensuring warehouses table: " . $e->getMessage() . "\n";
}
?>
```

### Controller Implementation
**File:** `erp-app/app/controllers/WarehouseController.php`

```php
<?php
require_once __DIR__ . '/../helpers/auth.php';

class WarehouseController {
    public function __construct() {}

    public function index() {
        global $db;
        $title = 'Raktárak kezelése';
        $error = '';
        $success = '';
        
        // Handle flash messages
        if (isset($_SESSION['warehouses_success'])) {
            $success = $_SESSION['warehouses_success'];
            unset($_SESSION['warehouses_success']);
        }
        if (isset($_SESSION['warehouses_error'])) {
            $error = $_SESSION['warehouses_error'];
            unset($_SESSION['warehouses_error']);
        }
        
        // Fetch all records
        $warehouses = $db->fetchAll("SELECT * FROM warehouses WHERE is_active = 1 ORDER BY name");
        
        ob_start();
        include __DIR__ . '/../views/warehouses/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/base.php';
    }

    public function add() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: warehouses.php');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $status = $_POST['status'] ?? 'active';
        
        // Validation
        if (empty($name)) {
            $_SESSION['warehouses_error'] = 'A név mező kitöltése kötelező!';
            header('Location: warehouses.php');
            exit;
        }
        
        // Check for duplicates
        $existing = $db->fetch("SELECT id FROM warehouses WHERE name = ? AND is_active = 1", [$name]);
        if ($existing) {
            $_SESSION['warehouses_error'] = 'Ez a név már létezik!';
            header('Location: warehouses.php');
            exit;
        }
        
        try {
            $db->query("INSERT INTO warehouses (name, country, postal_code, city, address, status) VALUES (?, ?, ?, ?, ?, ?)", 
                       [$name, $country, $postal_code, $city, $address, $status]);
            $_SESSION['warehouses_success'] = 'Raktár sikeresen hozzáadva!';
        } catch (Exception $e) {
            $_SESSION['warehouses_error'] = 'Hiba történt a mentés során: ' . $e->getMessage();
        }
        
        header('Location: warehouses.php');
        exit;
    }

    public function delete() {
        global $db;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: warehouses.php');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['warehouses_error'] = 'Érvénytelen azonosító!';
            header('Location: warehouses.php');
            exit;
        }
        
        try {
            $db->query("UPDATE warehouses SET is_active = 0 WHERE id = ?", [$id]);
            $_SESSION['warehouses_success'] = 'Raktár sikeresen törölve!';
        } catch (Exception $e) {
            $_SESSION['warehouses_error'] = 'Hiba történt a törlés során: ' . $e->getMessage();
        }
        
        header('Location: warehouses.php');
        exit;
    }
}
?>
```

### View Implementation
**File:** `erp-app/app/views/warehouses/index.php`

```php
<?php
if (!isset($warehouses)) {
    $warehouses = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Raktárak kezelése</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            <i class="ri ri-add-line me-2"></i>Új Raktár
        </button>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="ri ri-check-line me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="ri ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Név</th>
                            <th>Státusz</th>
                            <th class="text-end">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($warehouses)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="ri ri-store-line icon-3x mb-3"></i>
                                    <p>Nincsenek raktárak</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ri ri-store-line me-2 text-primary"></i>
                                            <?= htmlspecialchars($warehouse['name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($warehouse['status'] === 'active'): ?>
                                            <span class="badge bg-success">Aktív</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inaktív</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#warehouseInfoModal"
                                                    data-warehouse='<?= json_encode($warehouse) ?>'>
                                                <i class="ri ri-information-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteWarehouse(<?= $warehouse['id'] ?>)">
                                                <i class="ri ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Új Raktár</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="warehouses.php?action=add" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Név *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="country" class="form-label">Ország *</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="postal_code" class="form-label">Irányítószám *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="city" class="form-label">Város *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Utca, Házszám *</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="status" class="form-label">Státusz</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active">Aktív</option>
                                <option value="inactive">Inaktív</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div class="modal fade" id="warehouseInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Raktár Részletek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Név</label>
                        <p id="info-name" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Ország</label>
                        <p id="info-country" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">Irányítószám</label>
                        <p id="info-postal_code" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">Város</label>
                        <p id="info-city" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Utca, Házszám</label>
                        <p id="info-address" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Státusz</label>
                        <p id="info-status" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteWarehouseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Raktár Törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretné ezt a raktárat?</p>
                <p class="text-muted">Ez a művelet nem vonható vissza.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form action="warehouses.php?action=delete" method="POST" style="display: inline;">
                    <input type="hidden" id="delete-warehouse-id" name="id" value="">
                    <button type="submit" class="btn btn-danger">Törlés</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteWarehouse(id) {
    document.getElementById('delete-warehouse-id').value = id;
    new bootstrap.Modal(document.getElementById('deleteWarehouseModal')).show();
}

// Handle warehouse info modal
document.addEventListener('DOMContentLoaded', function() {
    const warehouseInfoModal = document.getElementById('warehouseInfoModal');
    if (warehouseInfoModal) {
        warehouseInfoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const warehouseData = JSON.parse(button.getAttribute('data-warehouse'));
            
            document.getElementById('info-name').textContent = warehouseData.name;
            document.getElementById('info-country').textContent = warehouseData.country;
            document.getElementById('info-postal_code').textContent = warehouseData.postal_code;
            document.getElementById('info-city').textContent = warehouseData.city;
            document.getElementById('info-address').textContent = warehouseData.address;
            
            const statusText = warehouseData.status === 'active' ? 'Aktív' : 'Inaktív';
            const statusClass = warehouseData.status === 'active' ? 'text-success' : 'text-secondary';
            document.getElementById('info-status').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
        });
    }
});
</script>
```

### Entry Point
**File:** `erp-app/public/warehouses.php`

```php
<?php
/**
 * Warehouses Entry Point
 */

require_once '../config/app.php';
require_once '../config/session.php';

session_start();

require_once '../database/connection.php';
require_once '../app/helpers/validation.php';
require_once '../app/helpers/flash.php';
require_once '../app/helpers/auth.php';

requirePageAccess('warehouses');

$path = '/warehouses';

// Handle actions
$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/WarehouseController.php';
    $controller = new WarehouseController();
    $controller->add();
} elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/WarehouseController.php';
    $controller = new WarehouseController();
    $controller->delete();
} else {
    require_once '../routes/web.php';
}
?>
```

### Tenant Integration
**File:** `saas-management/app/helpers/tenant.php` (add to table order):

```php
'warehouses',      // No dependencies
```

---

## Summary

This guide provides a complete step-by-step process for adding new CRUD functionality to the ERP system. The process includes:

1. **Database Setup** - Migration and ensure scripts
2. **Controller Creation** - CRUD logic with proper validation
3. **View Implementation** - UI with modals and tables
4. **Entry Point Configuration** - Action handling
5. **Route Configuration** - URL routing
6. **Tenant Creation Integration** - Multi-tenancy support
7. **Testing Process** - Verification scripts

Following this guide ensures consistent implementation across all new features and proper integration with the multi-tenant architecture. 