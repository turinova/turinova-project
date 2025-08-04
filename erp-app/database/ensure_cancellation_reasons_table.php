<?php
/**
 * Ensure cancellation_reasons table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'cancellation_reasons'");
    
    if (!$tableExists) {
        echo "Creating cancellation_reasons table in current tenant database...\n";
        
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
    } else {
        echo "Cancellation reasons table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM cancellation_reasons");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default cancellation reasons...\n";
            
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
            
            echo "Default cancellation reasons added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 