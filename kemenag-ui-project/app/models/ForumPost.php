<?php

namespace App\Models;

use Core\Model;

/**
 * ForumPost Model
 */
class ForumPost extends Model
{
    protected $table = 'forum_posts';
    
    public function getByTopic($topicId)
    {
        $sql = "SELECT fp.*, u.username, u.full_name, u.profile_picture
                FROM {$this->table} fp
                LEFT JOIN users u ON fp.user_id = u.id
                WHERE fp.topic_id = :topic_id AND fp.is_approved = 1
                ORDER BY fp.created_at ASC";
        
        return $this->db->query($sql)->bind(':topic_id', $topicId)->fetchAll();
    }
    
    public function createPost($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
}
