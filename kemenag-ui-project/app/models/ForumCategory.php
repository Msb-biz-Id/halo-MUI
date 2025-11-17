<?php

namespace App\Models;

use Core\Model;

/**
 * ForumCategory Model
 */
class ForumCategory extends Model
{
    protected $table = 'forum_categories';
    
    public function getAllOrdered()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY `order` ASC, name ASC";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getBySlug($slug)
    {
        return $this->findOneBy('slug', $slug);
    }
    
    public function getWithTopicCount()
    {
        $sql = "SELECT fc.*, COUNT(ft.id) as topic_count 
                FROM {$this->table} fc
                LEFT JOIN forum_topics ft ON fc.id = ft.category_id
                GROUP BY fc.id
                ORDER BY fc.`order` ASC";
        
        return $this->db->query($sql)->fetchAll();
    }
}
