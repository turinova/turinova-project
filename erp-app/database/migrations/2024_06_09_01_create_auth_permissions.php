<?php
// Migration for authentication and permissions system
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../connection.php';

try {
    // Users table
    $db->query("CREATE TABLE IF NOT EXISTS users (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Pages table
    $db->query("CREATE TABLE IF NOT EXISTS pages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        title VARCHAR(255) NOT NULL,
        route VARCHAR(255) NOT NULL UNIQUE,
        icon VARCHAR(100),
        menu_order INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // User permissions table
    $db->query("CREATE TABLE IF NOT EXISTS user_permissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        page_id INT NOT NULL,
        can_access BOOLEAN DEFAULT FALSE,
        can_create BOOLEAN DEFAULT FALSE,
        can_edit BOOLEAN DEFAULT FALSE,
        can_delete BOOLEAN DEFAULT FALSE,
        can_view BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_page (user_id, page_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Insert default superuser
    $superuserPassword = password_hash('superuser123', PASSWORD_DEFAULT);
    $db->query("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES ('superuser', 'superuser@turinova.com', '$superuserPassword', 'Super', 'User', 'superuser')");

    // Insert default pages
    $defaultPages = [
        ['dashboard', 'Vezérlőpult', '/dashboard', 'ri-home-smile-line', 1],
        ['settings', 'Beállítások', '/settings', 'ri-settings-4-line', 2],
        ['users', 'Felhasználók', '/users', 'ri-user-line', 3],
        ['permissions', 'Jogosultságok', '/permissions', 'ri-shield-keyhole-line', 4],
        ['profile', 'Profil', '/profile', 'ri-user-settings-line', 5],
        ['logout', 'Kijelentkezés', '/logout', 'ri-logout-box-r-line', 6]
    ];
    foreach ($defaultPages as $page) {
        $db->query("INSERT IGNORE INTO pages (name, title, route, icon, menu_order) VALUES ('{$page[0]}', '{$page[1]}', '{$page[2]}', '{$page[3]}', {$page[4]})");
    }

    // Give superuser access to all pages
    $superuserId = $db->fetch("SELECT id FROM users WHERE username = 'superuser'")['id'];
    $pages = $db->fetchAll("SELECT id FROM pages");
    foreach ($pages as $page) {
        $db->query("INSERT IGNORE INTO user_permissions (user_id, page_id, can_access, can_create, can_edit, can_delete, can_view) VALUES ($superuserId, {$page['id']}, TRUE, TRUE, TRUE, TRUE, TRUE)");
    }

    echo "✅ Auth and permissions tables created.\n";
    echo "🔑 Superuser: superuser@turinova.com / superuser123\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 