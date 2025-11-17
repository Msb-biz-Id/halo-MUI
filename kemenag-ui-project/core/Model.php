<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Base Model Class
 * All models extend from this base class
 */
class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Find all records
     */
    public function findAll($orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find record by ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find records by conditions
     */
    public function findBy($conditions, $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        
        foreach ($conditions as $key => $value) {
            $sql .= " AND {$key} = :{$key}";
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find one record by conditions
     */
    public function findOneBy($conditions)
    {
        $results = $this->findBy($conditions, null, 1);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Create new record
     */
    public function create($data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        
        $placeholders = array_map(function($field) {
            return ":{$field}";
        }, $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data)
    {
        $fields = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " 
                WHERE {$this->primaryKey} = :id";
        
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Delete record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Execute raw query
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt;
    }
    
    /**
     * Count records
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE 1=1";
        
        foreach ($conditions as $key => $value) {
            $sql .= " AND {$key} = :{$key}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
