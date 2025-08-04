# Turinova ERP System - Project Documentation

## 📋 **Project Overview**
**Version:** 3.0  
**Last Updated:** December 2024  
**Status:** Frontend Complete - Moving to Backend Development

---

## 🏗️ **System Architecture**

### **✅ Technology Stack:**
- **Backend:** PHP 8.3+ (MAMP Environment)
- **Database:** MySQL 8.0+ (via PDO)
- **Frontend:** Materialize Admin Template (Bootstrap 5)
- **Server:** Apache (MAMP)
- **Authentication:** Session-based with role-based access control
- **File Structure:** MVC Pattern with direct entry points

### **✅ Project Structure:**
```
Turinova_project/
├── erp-app/
│   ├── app/
│   │   ├── controllers/          # Business logic
│   │   ├── helpers/             # Utility functions
│   │   ├── middleware/          # Request processing
│   │   ├── models/              # Data models
│   │   └── views/               # Presentation layer
│   ├── config/                  # Configuration files
│   ├── database/                # Database migrations & seeds
│   ├── public/                  # Entry points & assets
│   └── routes/                  # Route definitions
```

---

## 🔐 **Authentication & Authorization System**

### **✅ Session Management:**
- **Session Lifetime:** 24 hours (86400 seconds)
- **Security:** HTTP-only cookies, SameSite=Strict
- **Session Regeneration:** Periodic ID regeneration for security
- **Cache Control:** Aggressive cache-busting headers

### **✅ User Roles:**
- **Superuser:** Full system access
- **Admin:** Administrative functions
- **User:** Basic access with permissions

### **✅ Permission System:**
- **Granular Control:** Page-level permissions (`can_access`)
- **Dynamic Menu:** Menu items show/hide based on permissions
- **Database-Driven:** All permissions stored in `user_permissions` table
- **Real-time Updates:** Permissions checked on each page load

### **✅ Security Features:**
- **Password Hashing:** `password_verify()` with bcrypt
- **Session Security:** Aggressive cleanup on logout
- **CSRF Protection:** Token-based form protection
- **Input Validation:** Server-side validation with helper functions

---

## 📚 **Development Guides**

### **✅ Function Creation Guide**
For adding new CRUD functionality to the ERP system, refer to the comprehensive guide:
- **File:** `FUNCTION_CREATION_GUIDE.md`
- **Covers:** Database setup, controller creation, view implementation, tenant integration
- **Includes:** Complete examples and step-by-step instructions

### **✅ Permission Control & Menu System**
For understanding the permission control and menu system implementation:
- **File:** `PERMISSION_CONTROL_DOCUMENTATION.md`
- **Covers:** Database structure, permission architecture, menu system, security features
- **Includes:** Multi-level permission checks, troubleshooting, and best practices

### **✅ Select2 UI Implementation**
For implementing enhanced dropdown functionality with Select2:
- **File:** `SELECT2_UI_IMPLEMENTATION_GUIDE.md`
- **Covers:** Setup, modal integration, troubleshooting, best practices
- **Includes:** Complete examples and configuration options

### **✅ Multi-Tenant Architecture**
- **Master Database:** `turinova_master` - Manages tenant information
- **Tenant Databases:** `turinova_{tenant_id}_erp` - Individual tenant data
- **Sample Database:** `turinova_sample_erp` - Template for new tenants
- **Tenant Creation:** Automatic database copying with data isolation

---

## 🗄️ **Database Schema**

### **✅ Core Tables:**

#### **`users` Table:**
```sql
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- role (superuser/admin/user)
- status (active/inactive)
- last_login (Timestamp)
- created_at (Timestamp)
```

#### **`pages` Table:**
```sql
- id (Primary Key)
- name (Unique identifier)
- title (Display name)
- route (URL path)
- icon (Remixicon class)
- menu_order (Sorting)
- is_active (Boolean)
- created_at (Timestamp)
```

#### **`user_permissions` Table:**
```sql
- id (Primary Key)
- user_id (Foreign Key)
- page_id (Foreign Key)
- can_access (Boolean)
- created_at (Timestamp)
```

### **✅ Current Pages (v3.0):**
1. **Dashboard** (`dashboard`) - Main control panel
2. **Users** (`users`) - User management
3. **Products** (`products`) - Product management
4. **Partners** (`partners`) - Partner management
5. **Shipments** (`shipments`) - Shipment tracking
6. **Supplier Orders** (`supplier_orders`) - Purchase orders
7. **Warehouse** (`warehouse`) - Inventory management
8. **Customers** (`customers`) - Customer database
9. **Pricing Rules** (`pricing_rules`) - Price management
10. **Product Categories** (`product_categories`) - Category management
11. **Manufacturers** (`manufacturers`) - Brand management
12. **Units** (`units`) - Unit of measurement
13. **Warehouses** (`warehouses`) - Warehouse management
14. **Payment Methods** (`payment_methods`) - Payment options
15. **Customer Groups** (`customer_groups`) - Customer segmentation
16. **Shelves** (`shelves`) - Storage locations
17. **POS** (`pos`) - Point of sale
18. **Media** (`media`) - File management
19. **Sales** (`sales`) - Sales management
20. **Returns** (`returns`) - Return processing
21. **Offers** (`offers`) - Offer management
22. **Reports** (`reports`) - Reporting system
23. **Operational Settings** (`operational_settings`) - System settings
24. **Company Data** (`company_data`) - Company information
25. **VAT** (`vat`) - Tax management
26. **Currencies** (`currencies`) - Currency management
27. **Positions** (`positions`) - Job positions
28. **Employees** (`employees`) - Employee management
29. **Performance** (`performance`) - Performance tracking
30. **Sources** (`sources`) - Lead sources
31. **Return Reasons** (`return_reasons`) - Return reasons
32. **Fee Types** (`fee_types`) - Fee categories
33. **Cancellation Reasons** (`cancellation_reasons`) - Cancellation reasons
34. **Shipping Methods** (`shipping_methods`) - Shipping options

---

## 🧭 **Navigation System**

### **✅ Menu Structure:**

#### **Main Navigation:**
1. **Vezérlőpult** (Dashboard)
   - Értékesítések (Sales)
   - Visszaáru (Returns)
   - Ajánlatok (Offers)
   - POS (Point of Sale)
   - Riport (Reports)

2. **Beszerzés** (Procurement)
   - Beszállítói rendelések (Supplier Orders)
   - Szállítmányok (Shipments)

3. **Törzsadatok** (Master Data)
   - **Partnerek** (Partners)
     - Partnerek kezelése (Partner Management)
     - Vevőcsoportok (Customer Groups)
   - **Termékek** (Products)
     - Termékek kezelése (Product Management)
     - Kategóriák (Categories)
     - Gyártók (Manufacturers)
     - Egységek (Units)
     - Média (Media)
   - **Értékesítés** (Sales)
     - Árazási szabályok (Pricing Rules)
     - Forrás (Sources)
     - Visszaáru okok (Return Reasons)
     - Díj típusok (Fee Types)
     - Visszamondás Oka (Cancellation Reasons)
     - Szállítási módok (Shipping Methods)
   - **Áruforgalom** (Inventory)
     - Raktárak kezelése (Warehouse Management)
     - Polchelyek (Shelves)
   - **Pénzügy** (Finance)
     - Fizetési módok (Payment Methods)
     - ÁFA (VAT)
     - Pénznemek (Currencies)

4. **Emberi erőforrás** (Human Resources)
   - Beosztás (Positions)
   - Dolgozók (Employees)
   - Teljesítmény (Performance)

5. **Beállítások** (Settings)
   - Működési beállítások (Operational Settings)
   - Cégadatok (Company Data)
   - Felhasználók (Users)

### **✅ Menu Features:**
- **Dynamic Loading:** Menu items based on user permissions
- **Active States:** Proper highlighting of current page
- **Multi-level Support:** Nested menu structures
- **Permission-based Display:** Hidden items for unauthorized users
- **URL Mapping:** Hyphenated URLs to underscore database names

---

## 🔧 **Development Methods & Processes**

### **✅ Complete Page Creation Process (Step-by-Step):**

#### **Step 1: Database Migration Creation**
```bash
# Create migration file in database/migrations/
# Naming convention: YYYY_MM_DD_add_page_name.php
```

```php
<?php
require_once 'config/app.php';
require_once 'database/connection.php';

global $db;

echo "Adding [Page Name] page...\n";

try {
    // Add page to database
    $db->query("
        INSERT INTO pages (name, title, route, icon, menu_order, created_at) 
        VALUES ('page_name', 'Page Title', '/page-route', 'ri-icon-name', order_number, NOW())
    ");
    
    echo "✓ Added '[Page Title]' page\n";
    
    // Get the page ID
    $pageId = $db->lastInsertId();
    
    // Grant superuser permission
    $db->query("
        INSERT IGNORE INTO user_permissions (user_id, page_id, can_access, created_at) 
        VALUES (1, {$pageId}, 1, NOW())
    ");
    
    echo "✓ Granted superuser permission for '[Page Title]'\n";
    
    echo "Successfully created [Page Title] page!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

**Run Migration:**
```bash
php database/migrations/YYYY_MM_DD_add_page_name.php
```

#### **Step 2: Controller Creation**
```php
// File: app/controllers/PageNameController.php
<?php
require_once __DIR__ . '/../helpers/auth.php';

class PageNameController {
    public function __construct() {
        // Authentication is now handled by requirePageAccess() in the entry point
        // No need for manual session checks here
    }

    public function index() {
        // Set page title
        $title = 'Page Title';
        
        // Render the page view
        ob_start();
        include '../app/views/page-name/index.php';
        $content = ob_get_clean();
        
        include '../app/views/layout/base.php';
    }
}
?>
```

#### **Step 3: View Creation**
```php
// File: app/views/page-name/index.php
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Page Title</h4>
        <button type="button" class="btn btn-primary" disabled>
            <i class="ri ri-add-line me-2"></i>Új Item
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center py-5">
                <i class="ri ri-icon-name icon-5x text-muted mb-3"></i>
                <h5 class="text-muted">Page Title oldal</h5>
                <p class="text-muted">Ez az oldal jelenleg fejlesztés alatt áll.</p>
            </div>
        </div>
    </div>
</div>
```

#### **Step 4: Entry Point Creation**
```php
// File: public/page-name.php
<?php
/**
 * Page Name Entry Point
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
requirePageAccess('page_name');

// Set path for routing
$path = '/page-name';

// Load routes
require_once '../routes/web.php';
?>
```

#### **Step 5: Route Addition**
```php
// File: routes/web.php
// Add to the $routes array:
'/page-name' => ['PageNameController', 'index'],
```

#### **Step 6: Menu Integration**

**A. Add URL Mapping (if needed):**
```php
// File: app/views/layout/base.php
// Add to $page_name_mapping array (if URL has hyphens):
$page_name_mapping = [
    // ... existing mappings ...
    'page-name' => 'page_name'
];
```

**B. Add Page Exclusion Logic:**
```php
// File: app/views/layout/base.php
// Add to the page categorization logic:
} elseif ($page['name'] === 'page_name') {
    // Page will be handled in the specific menu section - don't add to regular pages
    // Skip adding to regularPages array
```

**C. Update Master Data Detection (if applicable):**
```php
// File: app/views/layout/base.php
// Add to $isMasterDataPage array if it's a master data page:
$isMasterDataPage = in_array($current_page, [
    // ... existing pages ...
    'page_name'
]);
```

**D. Add Menu Item:**
```php
// File: app/views/layout/base.php
// Add to the appropriate menu section:

// For regular pages (top-level menu):
<li class="menu-item<?= ($current_page === 'page_name') ? ' active' : '' ?>">
    <a href="/Turinova_project/erp-app/public<?= $page['route'] ?>.php" class="menu-link">
        <i class="menu-icon icon-base ri <?= $page['icon'] ?>"></i>
        <div data-i18n="<?= $page['title'] ?>"><?= $page['title'] ?></div>
    </a>
</li>

// For sub-menu pages (under existing menu):
<?php 
// Check if user has access to page
$pageNamePage = null;
foreach ($userPages as $page) {
    if ($page['name'] === 'page_name' && $page['can_access']) {
        $pageNamePage = $page;
        break;
    }
}
if ($pageNamePage):
?>
<li class="menu-item<?= ($current_page === 'page_name') ? ' active' : '' ?>">
    <a href="/Turinova_project/erp-app/public<?= $pageNamePage['route'] ?>.php" class="menu-link">
        <div data-i18n="<?= $pageNamePage['title'] ?>"><?= $pageNamePage['title'] ?></div>
    </a>
</li>
<?php endif; ?>
```

**E. Update Menu Active State:**
```php
// File: app/views/layout/base.php
// Update the parent menu active state to include the new page:
<li class="menu-item<?= (in_array($current_page, ['existing_pages', 'page_name'])) ? ' active open' : '' ?>">
```

#### **Step 7: Permission System Integration**

**A. Database Permissions:**
- Permissions are automatically created in Step 1
- Superuser gets access by default
- Other users need manual permission assignment

**B. Permission Check:**
- `requirePageAccess('page_name')` in entry point
- Menu visibility based on `can_access` permission
- Dynamic menu generation based on user permissions

#### **Step 8: Testing & Verification**

**A. Test Page Access:**
```bash
# Test direct access
http://localhost:8888/Turinova_project/erp-app/public/page-name.php

# Test menu navigation
# Click on menu item and verify it works
```

**B. Test Permissions:**
```bash
# Test with different user roles
# Verify menu visibility based on permissions
# Test active state when on the page
```

**C. Test Menu Integration:**
```bash
# Verify menu item appears in correct location
# Test active state when page is loaded
# Test menu stays open when on sub-pages
```

#### **Step 9: Cleanup**

**A. Delete Migration File:**
```bash
rm database/migrations/YYYY_MM_DD_add_page_name.php
```

**B. Verify All Files:**
- ✅ Database entry created
- ✅ Controller file exists
- ✅ View file exists
- ✅ Entry point file exists
- ✅ Route added
- ✅ Menu integration complete
- ✅ Permissions working

### **✅ Page Creation Checklist:**

#### **Database:**
- [ ] Migration file created and run
- [ ] Page added to `pages` table
- [ ] Superuser permission granted
- [ ] Migration file deleted

#### **Files:**
- [ ] Controller created (`app/controllers/PageNameController.php`)
- [ ] View created (`app/views/page-name/index.php`)
- [ ] Entry point created (`public/page-name.php`)
- [ ] Route added (`routes/web.php`)

#### **Menu Integration:**
- [ ] URL mapping added (if needed)
- [ ] Page exclusion logic added
- [ ] Master data detection updated (if applicable)
- [ ] Menu item added to correct section
- [ ] Active state logic updated

#### **Testing:**
- [ ] Page loads without errors
- [ ] Menu item appears in correct location
- [ ] Active state works when on page
- [ ] Permissions work for different users
- [ ] Menu stays open for sub-pages

### **✅ Common Issues & Solutions:**

#### **Issue: Page redirects to login**
**Solution:** Check that `flash.php` and `validation.php` helpers are loaded in entry point

#### **Issue: Menu item doesn't appear**
**Solution:** Verify user has `can_access` permission for the page

#### **Issue: Active state doesn't work**
**Solution:** Check URL mapping and page name comparison logic

#### **Issue: Menu doesn't stay open**
**Solution:** Verify page is included in parent menu's active state array

#### **Issue: Database connection error**
**Solution:** Ensure database connection is loaded before `requirePageAccess()`

### **✅ Naming Conventions:**

#### **Database:**
- **Page Name:** `snake_case` (e.g., `page_name`)
- **Route:** `/kebab-case` (e.g., `/page-name`)
- **Title:** `Proper Case` (e.g., `Page Title`)

#### **Files:**
- **Controller:** `PascalCaseController.php` (e.g., `PageNameController.php`)
- **View Directory:** `kebab-case` (e.g., `page-name/`)
- **Entry Point:** `kebab-case.php` (e.g., `page-name.php`)

#### **URLs:**
- **Database Name:** `snake_case` (e.g., `page_name`)
- **URL Name:** `kebab-case` (e.g., `page-name`)
- **Mapping:** `'page-name' => 'page_name'`

### **✅ Icon Selection:**
- Use Remix Icons (`ri-*`)
- Choose appropriate icon for page function
- Common icons: `ri-file-list-line`, `ri-user-line`, `ri-settings-line`, etc.

### **✅ Menu Order:**
- Use sequential numbers (40, 41, 42, etc.)
- Keep related pages together
- Consider logical grouping

---

## 🎨 **Frontend Features**

### **✅ UI Components:**
- **Materialize Template:** Professional admin interface
- **Bootstrap 5:** Responsive grid system
- **Remix Icons:** Consistent iconography
- **Custom CSS:** Brand-specific styling

### **✅ Interactive Features:**
- **Dynamic Menus:** Permission-based navigation
- **Modal Dialogs:** User management forms
- **AJAX Requests:** Asynchronous data loading
- **Form Validation:** Client and server-side validation

### **✅ User Management Interface:**
- **User Listing:** Paginated user table
- **Add User Modal:** Create new users
- **Edit Permissions Modal:** Manage user access
- **Change Password Modal:** Password management
- **Delete User:** Remove user accounts
- **Last Login Tracking:** User activity monitoring

---

## 🔄 **API Endpoints**

### **✅ User Management APIs:**
```
POST /users/add                    # Add new user
GET  /users/permissions            # Get user permissions
POST /users/permissions/update     # Update permissions
POST /users/password               # Change password
POST /users/delete                 # Delete user
```

### **✅ Authentication APIs:**
```
POST /login                        # User login
GET  /logout                       # User logout
GET  /dashboard                    # Dashboard access
```

---

## 🚀 **Backend Development Roadmap**

### **✅ Phase 1: Core Backend (Next Priority)**
1. **Database Models**
   - Create model classes for all entities
   - Implement CRUD operations
   - Add data validation

2. **API Development**
   - RESTful API endpoints
   - JSON response formatting
   - Error handling and logging

3. **Business Logic**
   - Implement core business rules
   - Add data processing functions
   - Create service layer

### **✅ Phase 2: Advanced Features**
1. **Data Management**
   - Import/Export functionality
   - Bulk operations
   - Data backup/restore

2. **Reporting System**
   - Dynamic report generation
   - Chart and graph integration
   - Export to PDF/Excel

3. **Integration**
   - Third-party API integration
   - Payment gateway integration
   - Email/SMS notifications

### **✅ Phase 3: Optimization**
1. **Performance**
   - Database query optimization
   - Caching implementation
   - Load balancing

2. **Security**
   - Advanced security measures
   - Audit logging
   - Penetration testing

---

## 📊 **Current Status**

### **✅ Completed:**
- ✅ Complete frontend structure
- ✅ Authentication system
- ✅ Permission management
- ✅ User management interface
- ✅ Navigation system
- ✅ 34 pages with proper routing
- ✅ Session management
- ✅ Security features

### **🔄 In Progress:**
- 🔄 Backend development planning
- 🔄 Database model design
- 🔄 API architecture planning

### **📋 Next Steps:**
1. **Database Models:** Create model classes for all entities
2. **API Development:** Implement RESTful endpoints
3. **Business Logic:** Add core functionality
4. **Data Management:** Implement CRUD operations
5. **Testing:** Unit and integration testing

---

## 🛠️ **Development Environment**

### **✅ Local Setup:**
- **Server:** MAMP (Apache + MySQL)
- **URL:** `http://localhost:8888/Turinova_project/erp-app/public/`
- **Database:** `turinova_erp`
- **PHP Version:** 8.3+
- **Session Path:** `/Applications/MAMP/htdocs/Turinova_project/erp-app/`

### **✅ Configuration Files:**
- `config/app.php` - Application settings
- `config/session.php` - Session configuration
- `database/connection.php` - Database connection
- `routes/web.php` - Route definitions

---

## 📝 **Notes for Backend Development**

### **✅ Database Considerations:**
- All tables use `created_at` timestamps
- Foreign key relationships properly defined
- Indexes on frequently queried columns
- Soft deletes for data integrity

### **✅ Security Considerations:**
- All user inputs validated and sanitized
- SQL injection prevention via prepared statements
- XSS protection via output escaping
- CSRF protection on all forms

### **✅ Performance Considerations:**
- Database queries optimized
- Session data minimized
- Asset caching implemented
- Lazy loading for large datasets

---

**🎯 Ready for Backend Development!**  
The frontend foundation is complete and robust. All necessary infrastructure is in place for implementing the backend business logic and data management systems.
