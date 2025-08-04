<?php
/**
 * Migration: Add cancellation_reasons table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
    // Create cancellation_reasons table
    $sql = "CREATE TABLE IF NOT EXISTS cancellation_reasons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text VARCHAR(255) NOT NULL COMMENT 'Szöveg',
        description TEXT COMMENT 'Megjegyzés',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->query($sql);
    
    // Insert some default cancellation reasons
    $defaultCancellationReasons = [
        ['text' => 'Vevő kérésére', 'description' => 'A vevő kérte a törlést'],
        ['text' => 'Hibás adatok', 'description' => 'Hibás vagy hiányos adatok miatt'],
        ['text' => 'Duplikált rendelés', 'description' => 'Duplikált rendelés törlése'],
        ['text' => 'Raktárkészlet hiány', 'description' => 'Nincs elegendő készlet'],
        ['text' => 'Technikai hiba', 'description' => 'Rendszerhiba miatt'],
        ['text' => 'Egyéb', 'description' => 'Egyéb okok miatt']
    ];
    
    foreach ($defaultCancellationReasons as $reason) {
        $db->query("INSERT INTO cancellation_reasons (text, description) VALUES (?, ?)", 
            [$reason['text'], $reason['description']]);
    }
    
    echo "Cancellation reasons table created successfully with default data!\n";
    
} catch (Exception $e) {
    echo "Error creating cancellation_reasons table: " . $e->getMessage() . "\n";
}
?> 