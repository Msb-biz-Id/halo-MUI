<?php

namespace App\Models;

use Core\Model;

/**
 * Book Model
 */
class Book extends Model
{
    protected $table = 'books';
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id ORDER BY year DESC";
        return $this->db->query($sql)->bind(':category_id', $categoryId)->fetchAll();
    }
    
    public function getBySlug($slug)
    {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.slug = :slug";
        return $this->db->query($sql)->bind(':slug', $slug)->fetch();
    }
    
    public function filterBooks($categoryId = null, $year = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        if ($year) {
            $sql .= " AND year = :year";
            $params[':year'] = $year;
        }
        
        $sql .= " ORDER BY title ASC";
        
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->fetchAll();
    }
    
    public function incrementDownload($id)
    {
        $sql = "UPDATE {$this->table} SET download_count = download_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
}
