<?php

namespace App\Models;

use Core\Model;

/**
 * Category Model
 * Handles categories for content
 */
class Category extends Model
{
    protected $table = 'categories';
    
    /**
     * Get categories by type
     * 
     * @param string $type
     * @return array
     */
    public function getByType($type)
    {
        return $this->findBy('type', $type);
    }
    
    /**
     * Get category by slug
     * 
     * @param string $slug
     * @return mixed
     */
    public function getBySlug($slug)
    {
        return $this->findOneBy('slug', $slug);
    }
    
    /**
     * Get category with parent
     * 
     * @param int $id
     * @return mixed
     */
    public function getWithParent($id)
    {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id 
                WHERE c.id = :id";
        
        return $this->db->query($sql)->bind(':id', $id)->fetch();
    }
    
    /**
     * Get categories with children
     * 
     * @param string $type
     * @return array
     */
    public function getWithChildren($type)
    {
        $sql = "SELECT c.*, COUNT(ch.id) as children_count 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} ch ON c.id = ch.parent_id 
                WHERE c.type = :type AND c.parent_id IS NULL 
                GROUP BY c.id 
                ORDER BY c.name";
        
        return $this->db->query($sql)->bind(':type', $type)->fetchAll();
    }
    
    /**
     * Get child categories
     * 
     * @param int $parentId
     * @return array
     */
    public function getChildren($parentId)
    {
        return $this->findBy('parent_id', $parentId);
    }
}
