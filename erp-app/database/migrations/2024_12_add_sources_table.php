<?php
/**
 * Migration: Add sources table
 * Date: 2024-12
 */

require_once __DIR__ . '/../connection.php';

try {
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
    
} catch (Exception $e) {
    echo "Error creating sources table: " . $e->getMessage() . "\n";
}
?> 