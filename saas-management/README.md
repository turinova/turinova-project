# 🏢 Turinova SaaS Management System

A separate multi-tenant SaaS management system for the Turinova ERP platform.

## 📋 Overview

This system manages tenant creation, authentication, and database isolation for the Turinova ERP SaaS platform. It's completely separate from the main ERP application to allow for independent development and deployment.

## 🏗️ Architecture

### **Multi-Tenant Database Structure:**
- **Master Database**: `turinova_master` - Manages all tenants
- **Tenant Databases**: `turinova_{tenant_id}_erp` - Individual tenant data
- **Complete Isolation**: Each tenant has their own database

### **Key Components:**
1. **Tenant Management**: Create, validate, and manage tenant databases
2. **Authentication**: Multi-tenant login with azonosito (tenant identifier)
3. **Database Isolation**: Separate database per tenant
4. **Session Management**: Tenant-aware session handling

## 📁 Project Structure

```
saas-management/
├── config/
│   ├── app.php              # SaaS configuration
│   └── session.php          # Session settings
├── database/
│   ├── connection.php       # Multi-tenant DB connection
│   └── migrations/          # Database migrations
├── app/
│   ├── controllers/         # Business logic
│   ├── helpers/            # Utility functions
│   └── views/              # Templates
├── public/                 # Entry points
└── storage/               # Logs and sessions
```

## 🚀 Installation

### **Step 1: Run Master Database Migration**
```bash
cd saas-management/database/migrations
php 2024_12_create_master_database.php
```

### **Step 2: Set Permissions**
```bash
chmod 755 saas-management/storage/
chmod 644 saas-management/public/.htaccess
```

### **Step 3: Test the System**
- Access: `http://localhost:8888/Turinova_project/saas-management/public/register.php`
- Create a new tenant
- Test login with the new tenant

## 🔧 Configuration

### **Database Settings** (`config/app.php`):
```php
// Multi-tenant database configuration
define('SAAS_DB_HOST', 'localhost');
define('SAAS_DB_USER', 'root');
define('SAAS_DB_PASS', 'root');

// Tenant database naming convention
define('SAAS_TENANT_DB_PREFIX', 'turinova_');
define('SAAS_TENANT_DB_SUFFIX', '_erp');

// Master database for tenant management
define('SAAS_MASTER_DB_NAME', 'turinova_master');
```

## 🔐 Authentication Flow

### **Login Process:**
1. User enters **Azonosító** (tenant identifier)
2. System validates tenant exists
3. Connects to tenant-specific database
4. Validates user credentials
5. Sets session with tenant context

### **Registration Process:**
1. User provides company details and azonosito
2. System validates azonosito format
3. Creates new tenant database
4. Initializes tenant schema
5. Creates default superuser account

## 📊 Database Schema

### **Master Database Tables:**
- `tenants` - Tenant information and status
- `tenant_users` - Cross-tenant user management
- `tenant_settings` - Tenant-specific settings
- `tenant_usage` - Usage tracking
- `tenant_subscriptions` - Subscription management

### **Tenant Database Tables:**
- `users` - Tenant-specific users
- `pages` - Available pages for tenant
- `user_permissions` - User access permissions

## 🛠️ Usage

### **Creating a New Tenant:**
1. Visit registration page
2. Enter company details and azonosito
3. System creates tenant database automatically
4. Default superuser: `superuser@turinova.com` / `superuser123`

### **Tenant Login:**
1. Enter azonosito (tenant identifier)
2. Enter username/email and password
3. System connects to tenant database
4. Validates credentials and sets session

## 🔒 Security Features

- **Tenant Isolation**: Complete database separation
- **Session Security**: Tenant-aware sessions
- **Input Validation**: Azonosito format validation
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Output escaping

## 🚀 Future Development

This system is designed to be independent of the main ERP application, allowing for:

1. **Separate Development**: Frontend, backend, and database can be developed independently
2. **Scalable Architecture**: Easy to add new tenants and features
3. **Modular Design**: Components can be updated independently
4. **API Integration**: Ready for future API development

## 📝 Notes

- The main ERP application (`erp-app/`) remains unchanged
- This SaaS system is completely separate
- Each tenant gets their own database with full isolation
- The system is ready for future frontend/backend development

## 🎯 Status

✅ **Completed:**
- Multi-tenant database architecture
- Tenant creation and management
- Authentication system with azonosito
- Database isolation
- Session management

🔄 **Ready for:**
- Frontend development
- Backend API development
- Database schema evolution
- Additional SaaS features 