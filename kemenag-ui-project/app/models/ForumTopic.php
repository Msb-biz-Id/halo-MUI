<?php

namespace App\Models;

use Core\Model;

/**
 * ForumTopic Model
 */
class ForumTopic extends Model
{
    protected $table = 'forum_topics';
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT ft.*, u.username as author_username, 
                COUNT(fp.id) as post_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
                WHERE ft.category_id = :category_id
                GROUP BY ft.id
                ORDER BY ft.is_sticky DESC, ft.last_post_at DESC";
        
        return $this->db->query($sql)->bind(':category_id', $categoryId)->fetchAll();
    }
    
    public function getBySlug($slug)
    {
        $sql = "SELECT ft.*, u.username as author_username, u.full_name as author_name,
                fc.name as category_name
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN forum_categories fc ON ft.category_id = fc.id
                WHERE ft.slug = :slug";
        
        return $this->db->query($sql)->bind(':slug', $slug)->fetch();
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    public function updateLastPost($topicId, $userId)
    {
        $data = [
            'last_post_at' => date('Y-m-d H:i:s'),
            'last_post_by' => $userId
        ];
        
        return $this->update($topicId, $data);
    }
}
