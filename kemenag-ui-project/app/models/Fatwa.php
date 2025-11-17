<?php

namespace App\Models;

use Core\Model;

/**
 * Fatwa Model
 */
class Fatwa extends Model
{
    protected $table = 'fatwas';
    
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
        $sql = "SELECT f.*, u.full_name as author_name, c.name as category_name 
                FROM {$this->table} f
                LEFT JOIN users u ON f.author_id = u.id
                LEFT JOIN categories c ON f.category_id = c.id
                WHERE f.slug = :slug";
        return $this->db->query($sql)->bind(':slug', $slug)->fetch();
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    public function getPopular($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY view_count DESC LIMIT :limit";
        $this->db->query($sql)->bind(':limit', $limit, \PDO::PARAM_INT);
        return $this->db->fetchAll();
    }
}
