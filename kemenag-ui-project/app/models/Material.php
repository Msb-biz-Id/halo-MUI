<?php

namespace App\Models;

use Core\Model;

/**
 * Material Model
 */
class Material extends Model
{
    protected $table = 'materials';
    
    public function getPublished()
    {
        return $this->findBy('is_published', 1);
    }
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id AND is_published = 1 ORDER BY published_at DESC";
        return $this->db->query($sql)->bind(':category_id', $categoryId)->fetchAll();
    }
    
    public function getBySlug($slug)
    {
        $sql = "SELECT m.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} m
                LEFT JOIN users u ON m.author_id = u.id
                LEFT JOIN categories c ON m.category_id = c.id
                WHERE m.slug = :slug";
        return $this->db->query($sql)->bind(':slug', $slug)->fetch();
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    public function getByType($type)
    {
        return $this->findBy('type', $type);
    }
}
