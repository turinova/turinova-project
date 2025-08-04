# Permission Control and Menu System Documentation

## Table of Contents
1. [Overview](#overview)
2. [Database Structure](#database-structure)
3. [Permission System Architecture](#permission-system-architecture)
4. [Menu System](#menu-system)
5. [Permission Levels](#permission-levels)
6. [Implementation Details](#implementation-details)
7. [Menu Categories](#menu-categories)
8. [User Roles](#user-roles)
9. [Security Features](#security-features)
10. [Troubleshooting](#troubleshooting)

---

## Overview

The ERP system implements a comprehensive **Role-Based Access Control (RBAC)** system with a **multi-level menu structure** that dynamically displays menu items based on user permissions. The system ensures that users only see and can access functionality they have permission to use.

### Key Features
- ✅ **Multi-level menu permissions** (Level 1, 2, 3)
- ✅ **Dynamic menu rendering** based on user permissions
- ✅ **Session-based authentication**
- ✅ **Database-driven permissions**
- ✅ **Hierarchical menu structure**

---

## Database Structure

### Core Tables

#### 1. `users` Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('superuser', 'admin', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 2. `pages` Table
```sql
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    route VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(100),
    menu_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 3. `user_permissions` Table
```sql
CREATE TABLE user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    page_id INT NOT NULL,
    can_access BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_page (user_id, page_id)
);
```

---

## Permission System Architecture

### Permission Flow
```
User Login → Session Creation → Permission Check → Menu Rendering → Page Access
```

### Permission Check Process
1. **User Authentication**: User logs in and session is created
2. **Permission Loading**: System loads user permissions from database
3. **Menu Rendering**: Menu items are filtered based on permissions
4. **Page Access**: Individual page access is validated

### Permission Query
```php
// Get user permissions for all pages
$permissions = $db->fetchAll("
    SELECT p.name, up.can_access
    FROM pages p
    LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
    ORDER BY p.menu_order
", [$_SESSION['user_id']]);
```

---

## Menu System

### Menu Structure Levels

#### Level 1: Main Menu Categories
- **Dashboard** - Main dashboard page
- **Törzsadatok** - Master data management
- **Beállítások** - Settings and configuration
- **Emberi erőforrás** - Human resources
- **Beszerzés** - Procurement

#### Level 2: Submenu Categories (within Törzsadatok)
- **Partnerek** - Partner management
- **Termékek** - Product management
- **Értékesítés** - Sales management
- **Áruforgalom** - Inventory management
- **Pénzügy** - Financial management

#### Level 3: Individual Pages
- Specific functionality pages within each category

### Menu Permission Logic

#### Level 1 Permission Check
```php
// Check if user has access to any master data pages
$hasMasterDataAccess = false;
foreach ($userPages as $page) {
    if (in_array($page["name"], ["partners", "products", "product_categories", "manufacturers", "units", "media", "pricing_rules", "sources", "return_reasons", "warehouses", "payment_methods", "customer_groups", "shelves", "vat", "currencies", "fee_types", "cancellation_reasons", "shipping_methods"]) && $page["can_access"]) {
        $hasMasterDataAccess = true;
        break;
    }
}

if ($hasMasterDataAccess):
    // Show Törzsadatok menu
endif;
```

#### Level 2 Permission Check
```php
// Example: Check if user has access to any partner-related pages
$hasPartnerAccess = false;
foreach ($userPages as $page) {
    if (in_array($page["name"], ["partners", "customer_groups"]) && $page["can_access"]) {
        $hasPartnerAccess = true;
        break;
    }
}

if ($hasPartnerAccess):
    // Show "Partnerek" submenu
endif;
```

#### Level 3 Permission Check
```php
// Check if user has access to specific page
$partnersPage = null;
foreach ($userPages as $page) {
    if ($page["name"] === "partners" && $page["can_access"]) {
        $partnersPage = $page;
        break;
    }
}

if ($partnersPage):
    // Show individual page link
endif;
```

---

## Permission Levels

### 1. Superuser
- **Access**: All pages and functionality
- **Permissions**: Full system access
- **Menu**: Complete menu structure

### 2. Admin
- **Access**: Most pages (configurable)
- **Permissions**: High-level access
- **Menu**: Extensive menu options

### 3. User
- **Access**: Limited pages (configurable)
- **Permissions**: Basic access
- **Menu**: Restricted menu options

---

## Implementation Details

### Menu Rendering Process

#### 1. Permission Loading
```php
// Load user permissions
$user_permissions = [];
if (isset($_SESSION['user_id'])) {
    $permissions = $db->fetchAll("
        SELECT p.name, up.can_access
        FROM pages p
        LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
        ORDER BY p.menu_order
    ", [$_SESSION['user_id']]);
    
    foreach ($permissions as $perm) {
        $user_permissions[$perm['name']] = $perm['can_access'] ?? 0;
    }
}
```

#### 2. Menu Item Filtering
```php
// Filter pages based on permissions
$userPages = $db->fetchAll("
    SELECT p.name, p.title, p.route, p.icon, p.menu_order,
           COALESCE(up.can_access, 0) as can_access
    FROM pages p
    LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
    ORDER BY p.menu_order
", [$_SESSION['user_id']]);
```

#### 3. Dynamic Menu Generation
```php
// Generate menu items based on permissions
foreach ($userPages as $page):
    if ($page['can_access']):
        // Render menu item
    endif;
endforeach;
```

### Current Page Detection
```php
// Get current path for active menu highlighting
$current_path = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($current_path, PHP_URL_PATH);

// Extract the page name from the path
$current_page = '';
if (preg_match('/\/([^\/]+)\.php$/', $path, $matches)) {
    $current_page = $matches[1];
}
```

---

## Menu Categories

### Törzsadatok (Master Data)

#### Partnerek (Partners)
- **Pages**: `partners`, `customer_groups`
- **Permission Check**: Access to any partner-related page
- **Description**: Partner and customer management

#### Termékek (Products)
- **Pages**: `products`, `product_categories`, `manufacturers`, `units`, `media`
- **Permission Check**: Access to any product-related page
- **Description**: Product catalog and related data

#### Értékesítés (Sales)
- **Pages**: `pricing_rules`, `sources`, `return_reasons`, `fee_types`, `cancellation_reasons`, `shipping_methods`
- **Permission Check**: Access to any sales-related page
- **Description**: Sales configuration and management

#### Áruforgalom (Inventory)
- **Pages**: `warehouses`, `shelves`
- **Permission Check**: Access to any warehouse-related page
- **Description**: Inventory and warehouse management

#### Pénzügy (Finance)
- **Pages**: `payment_methods`, `vat`, `currencies`
- **Permission Check**: Access to any finance-related page
- **Description**: Financial configuration

### Beállítások (Settings)
- **Pages**: `users`, `operational_settings`, `company_data`
- **Permission Check**: Access to any settings page
- **Description**: System configuration and user management

### Emberi erőforrás (Human Resources)
- **Pages**: `positions`, `employees`, `performance`
- **Permission Check**: Access to any HR page
- **Description**: Employee and HR management

### Beszerzés (Procurement)
- **Pages**: `supplier_orders`, `shipments`
- **Permission Check**: Access to any procurement page
- **Description**: Supplier and procurement management

---

## User Roles

### Superuser
```php
// Default superuser permissions
$superuserPermissions = [
    'dashboard' => true,
    'users' => true,
    'partners' => true,
    'products' => true,
    'product_categories' => true,
    'manufacturers' => true,
    'units' => true,
    'media' => true,
    'pricing_rules' => true,
    'sources' => true,
    'return_reasons' => true,
    'fee_types' => true,
    'cancellation_reasons' => true,
    'shipping_methods' => true,
    'warehouses' => true,
    'shelves' => true,
    'payment_methods' => true,
    'vat' => true,
    'currencies' => true,
    'customer_groups' => true,
    'operational_settings' => true,
    'company_data' => true,
    'positions' => true,
    'employees' => true,
    'performance' => true,
    'supplier_orders' => true,
    'shipments' => true
];
```

### Admin
- **Default Permissions**: Most pages except sensitive settings
- **Menu Access**: Extensive menu options
- **Functionality**: High-level management capabilities

### User
- **Default Permissions**: Limited to basic functionality
- **Menu Access**: Restricted menu options
- **Functionality**: Basic operational tasks

---

## Security Features

### 1. Session-Based Authentication
```php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
```

### 2. Permission Validation
```php
// Page access validation
function checkPageAccess($pageName) {
    global $user_permissions;
    return isset($user_permissions[$pageName]) && $user_permissions[$pageName];
}
```

### 3. SQL Injection Prevention
```php
// Prepared statements for all database queries
$permissions = $db->fetchAll("
    SELECT p.name, up.can_access
    FROM pages p
    LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
    ORDER BY p.menu_order
", [$_SESSION['user_id']]);
```

### 4. CSRF Protection
```php
// CSRF token validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

---

## Troubleshooting

### Common Issues

#### 1. Menu Items Not Showing
**Problem**: User can't see expected menu items
**Solution**: Check user permissions in database
```sql
SELECT p.name, p.title, up.can_access
FROM pages p
LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
WHERE up.user_id = [USER_ID];
```

#### 2. Level 2 Menu Items Always Visible
**Problem**: Submenu categories show even when user has no access
**Solution**: Verify permission checks are implemented correctly
```php
// Ensure Level 2 permission checks are in place
$hasCategoryAccess = false;
foreach ($userPages as $page) {
    if (in_array($page["name"], $categoryPages) && $page["can_access"]) {
        $hasCategoryAccess = true;
        break;
    }
}
```

#### 3. Permission Changes Not Reflecting
**Problem**: Permission updates not taking effect
**Solution**: Clear session and re-login
```php
// Clear session data
session_destroy();
session_start();
```

### Debug Tools

#### 1. Permission Debug Script
```php
// Debug user permissions
function debugUserPermissions($userId) {
    global $db;
    
    $permissions = $db->fetchAll("
        SELECT p.name, p.title, up.can_access
        FROM pages p
        LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
        ORDER BY p.menu_order
    ", [$userId]);
    
    foreach ($permissions as $perm) {
        echo "Page: {$perm['title']} ({$perm['name']}) - Access: " . ($perm['can_access'] ? 'Yes' : 'No') . "\n";
    }
}
```

#### 2. Menu Debug Information
```php
// Add debug info to menu rendering
if (isset($_GET['debug_menu'])) {
    echo "<!-- Debug: User ID: {$_SESSION['user_id']} -->\n";
    echo "<!-- Debug: Current Page: {$current_page} -->\n";
    echo "<!-- Debug: User Pages Count: " . count($userPages) . " -->\n";
}
```

### Best Practices

1. **Always validate permissions** before rendering menu items
2. **Use prepared statements** for all database queries
3. **Implement proper error handling** for permission checks
4. **Test with different user roles** to ensure proper access control
5. **Log permission changes** for audit purposes
6. **Regular permission audits** to maintain security

---

## File Structure

### Core Files
```
erp-app/
├── app/
│   ├── views/
│   │   └── layout/
│   │       └── base.php          # Main layout with menu logic
│   └── controllers/
│       └── UserController.php     # User and permission management
├── database/
│   └── migrations/
│       └── 2024_06_09_01_create_auth_permissions.php  # Permission tables
└── public/
    └── users-permissions.php      # Permission management interface
```

### Key Functions
- **Menu Rendering**: `base.php` (lines 356-802)
- **Permission Loading**: `base.php` (lines 30-50)
- **User Management**: `UserController.php`
- **Permission Updates**: `users-permissions.php`

---

## Conclusion

The permission control and menu system provides a robust, secure, and user-friendly way to manage access to ERP functionality. The multi-level permission system ensures that users only see and can access what they're authorized to use, while the dynamic menu rendering provides a clean and intuitive user experience.

The system is designed to be:
- **Scalable**: Easy to add new pages and permissions
- **Secure**: Multiple layers of access control
- **Maintainable**: Clear separation of concerns
- **User-friendly**: Intuitive menu structure
- **Flexible**: Configurable permissions per user 