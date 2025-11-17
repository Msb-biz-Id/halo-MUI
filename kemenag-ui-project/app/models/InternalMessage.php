<?php

namespace App\Models;

use Core\Model;

/**
 * InternalMessage Model
 */
class InternalMessage extends Model
{
    protected $table = 'internal_messages';
    
    public function getConversation($userId1, $userId2)
    {
        $sql = "SELECT im.*, 
                sender.username as sender_username, sender.full_name as sender_name,
                receiver.username as receiver_username, receiver.full_name as receiver_name
                FROM {$this->table} im
                LEFT JOIN users sender ON im.sender_id = sender.id
                LEFT JOIN users receiver ON im.receiver_id = receiver.id
                WHERE (im.sender_id = :user1 AND im.receiver_id = :user2)
                   OR (im.sender_id = :user2 AND im.receiver_id = :user1)
                ORDER BY im.created_at ASC";
        
        $this->db->query($sql);
        $this->db->bind(':user1', $userId1);
        $this->db->bind(':user2', $userId2);
        
        return $this->db->fetchAll();
    }
    
    public function getInbox($userId)
    {
        $sql = "SELECT im.*, u.username as sender_username, u.full_name as sender_name
                FROM {$this->table} im
                LEFT JOIN users u ON im.sender_id = u.id
                WHERE im.receiver_id = :user_id
                ORDER BY im.created_at DESC";
        
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    public function getSent($userId)
    {
        $sql = "SELECT im.*, u.username as receiver_username, u.full_name as receiver_name
                FROM {$this->table} im
                LEFT JOIN users u ON im.receiver_id = u.id
                WHERE im.sender_id = :user_id
                ORDER BY im.created_at DESC";
        
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    public function markAsRead($id)
    {
        return $this->update($id, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function countUnread($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE receiver_id = :user_id AND is_read = 0";
        $result = $this->db->query($sql)->bind(':user_id', $userId)->fetch();
        return (int) $result['count'];
    }
}
