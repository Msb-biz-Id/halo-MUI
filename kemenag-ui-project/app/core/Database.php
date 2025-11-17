<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Database Class
 * Handles database connections and queries using PDO
 */
class Database
{
    private static $instance = null;
    private $connection;
    private $statement;
    
    /**
     * Constructor - Private to enforce singleton pattern
     */
    private function __construct()
    {
        $dsn = DB_CONNECTION . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE . ';charset=' . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];
        
        try {
            $this->connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }
    
    /**
     * Get singleton instance
     * 
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO connection
     * 
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Prepare SQL query
     * 
     * @param string $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->statement = $this->connection->prepare($sql);
        return $this;
    }
    
    /**
     * Bind value to prepared statement
     * 
     * @param mixed $param
     * @param mixed $value
     * @param int|null $type
     * @return $this
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->statement->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Execute prepared statement
     * 
     * @return bool
     */
    public function execute()
    {
        try {
            return $this->statement->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }
    
    /**
     * Fetch all results
     * 
     * @return array
     */
    public function fetchAll()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }
    
    /**
     * Fetch single result
     * 
     * @return mixed
     */
    public function fetch()
    {
        $this->execute();
        return $this->statement->fetch();
    }
    
    /**
     * Fetch single column value
     * 
     * @param int $column
     * @return mixed
     */
    public function fetchColumn($column = 0)
    {
        $this->execute();
        return $this->statement->fetchColumn($column);
    }
    
    /**
     * Get row count
     * 
     * @return int
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    
    /**
     * Get last insert ID
     * 
     * @return string
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }
    
    /**
     * Handle database errors
     * 
     * @param PDOException $e
     * @throws PDOException
     */
    private function handleError(PDOException $e)
    {
        // Log error
        error_log('Database Error: ' . $e->getMessage());
        
        // Show user-friendly error in development
        if (APP_DEBUG) {
            die('Database Error: ' . $e->getMessage());
        } else {
            die('Database connection error. Please contact administrator.');
        }
    }
    
    /**
     * Prevent cloning of singleton
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization of singleton
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
