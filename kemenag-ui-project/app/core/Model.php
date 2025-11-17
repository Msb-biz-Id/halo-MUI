<?php

namespace Core;

use Core\Database;

/**
 * Base Model Class
 * All models extend this class
 */
class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find all records
     * 
     * @return array
     */
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * Find record by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->query($sql)->bind(':id', $id)->fetch();
    }
    
    /**
     * Find records by column value
     * 
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public function findBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        return $this->db->query($sql)->bind(':value', $value)->fetchAll();
    }
    
    /**
     * Find single record by column value
     * 
     * @param string $column
     * @param mixed $value
     * @return mixed
     */
    public function findOneBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->query($sql)->bind(':value', $value)->fetch();
    }
    
    /**
     * Insert record
     * 
     * @param array $data
     * @return int Last insert ID
     */
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        
        $this->db->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');
        
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = :id";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Delete record
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    /**
     * Count all records
     * 
     * @return int
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->query($sql)->fetch();
        return (int) $result['count'];
    }
    
    /**
     * Count records with WHERE condition
     * 
     * @param string $column
     * @param mixed $value
     * @return int
     */
    public function countBy($column, $value)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} = :value";
        $result = $this->db->query($sql)->bind(':value', $value)->fetch();
        return (int) $result['count'];
    }
    
    /**
     * Paginate records
     * 
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        $this->db->bind(':limit', $perPage, \PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
        
        return $this->db->fetchAll();
    }
    
    /**
     * Search records with LIKE
     * 
     * @param string $column
     * @param string $term
     * @return array
     */
    public function search($column, $term)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} LIKE :term";
        return $this->db->query($sql)->bind(':term', "%{$term}%")->fetchAll();
    }
    
    /**
     * Execute raw query
     * 
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, $params = [])
    {
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db;
    }
    
    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit()
    {
        return $this->db->commit();
    }
    
    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollback()
    {
        return $this->db->rollback();
    }
}
