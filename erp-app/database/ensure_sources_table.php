<?php
/**
 * Ensure sources table exists in current tenant database
 */

// Load configuration and database connection
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
session_start();
require_once __DIR__ . '/connection.php';

try {
    // Check if table exists
    $tableExists = $db->fetch("SHOW TABLES LIKE 'sources'");
    
    if (!$tableExists) {
        echo "Creating sources table in current tenant database...\n";
        
        // Create sources table
        $sql = "CREATE TABLE IF NOT EXISTS sources (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL COMMENT 'Név',
            description TEXT COMMENT 'Megjegyzés',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->query($sql);
        
        // Insert some default sources
        $defaultSources = [
            ['name' => 'Weboldal', 'description' => 'Vevők a weboldalról'],
            ['name' => 'Facebook', 'description' => 'Vevők Facebook-ról'],
            ['name' => 'Instagram', 'description' => 'Vevők Instagram-ról'],
            ['name' => 'Ajánlás', 'description' => 'Ajánlás útján érkezett vevők'],
            ['name' => 'Google', 'description' => 'Google keresésből érkezett vevők'],
            ['name' => 'Egyéb', 'description' => 'Egyéb forrásokból érkezett vevők']
        ];
        
        foreach ($defaultSources as $source) {
            $db->query("INSERT INTO sources (name, description) VALUES (?, ?)", 
                [$source['name'], $source['description']]);
        }
        
        echo "Sources table created successfully with default data!\n";
    } else {
        echo "Sources table already exists.\n";
        
        // Check if table has data
        $count = $db->fetch("SELECT COUNT(*) as count FROM sources");
        echo "Table contains " . $count['count'] . " records.\n";
        
        if ($count['count'] == 0) {
            echo "Adding default sources...\n";
            
            $defaultSources = [
                ['name' => 'Weboldal', 'description' => 'Vevők a weboldalról'],
                ['name' => 'Facebook', 'description' => 'Vevők Facebook-ról'],
                ['name' => 'Instagram', 'description' => 'Vevők Instagram-ról'],
                ['name' => 'Ajánlás', 'description' => 'Ajánlás útján érkezett vevők'],
                ['name' => 'Google', 'description' => 'Google keresésből érkezett vevők'],
                ['name' => 'Egyéb', 'description' => 'Egyéb forrásokból érkezett vevők']
            ];
            
            foreach ($defaultSources as $source) {
                $db->query("INSERT INTO sources (name, description) VALUES (?, ?)", 
                    [$source['name'], $source['description']]);
            }
            
            echo "Default sources added successfully!\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 