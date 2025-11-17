<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Database Connection Class
 * Handle database connections using PDO
 */
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;
    private $connection;
    
    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'] ?? 'utf8mb4';
    }
    
    /**
     * Create PDO connection
     */
    public function connect()
    {
        if ($this->connection === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                $this->connection = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
        
        return $this->connection;
    }
    
    /**
     * Get connection
     */
    public function getConnection()
    {
        return $this->connect();
    }
}
