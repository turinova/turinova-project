<?php
/**
 * Multi-Tenant Database Connection for SaaS Management
 * 
 * Provides PDO connection to MySQL database with tenant support
 */

class MultiTenantDatabase {
    private static $instances = [];
    private $connection;
    private $tenantId;
    private $databaseName;
    
    private function __construct($tenantId = null, $databaseName = null) {
        $this->tenantId = $tenantId;
        $this->databaseName = $databaseName;
        
        try {
            // Determine database name
            if ($databaseName) {
                $dbName = $databaseName;
            } elseif ($tenantId) {
                $dbName = SAAS_TENANT_DB_PREFIX . $tenantId . SAAS_TENANT_DB_SUFFIX;
            } else {
                $dbName = SAAS_DEFAULT_DB_NAME;
            }
            
            // Use MAMP's MySQL socket for connection
            $dsn = "mysql:host=localhost;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=" . $dbName . ";charset=" . SAAS_DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, SAAS_DB_USER, SAAS_DB_PASS, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance($tenantId = null, $databaseName = null) {
        $key = $tenantId ?: $databaseName ?: 'default';
        
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new self($tenantId, $databaseName);
        }
        return self::$instances[$key];
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function getTenantId() {
        return $this->tenantId;
    }
    
    public function getDatabaseName() {
        return $this->databaseName;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollback();
    }
}

// Master database instance for tenant management
class MasterDatabase {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=localhost;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=" . SAAS_MASTER_DB_NAME . ";charset=" . SAAS_DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, SAAS_DB_USER, SAAS_DB_PASS, $options);
        } catch (PDOException $e) {
            die("Master database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
}

// Global database instance (default for backward compatibility)
$db = MultiTenantDatabase::getInstance();
?> 