<?php

namespace App\Models;

use Core\Model;

/**
 * Notification Model
 */
class Notification extends Model
{
    protected $table = 'notifications';
    
    public function getByUser($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    public function createNotification($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        
        return $this->insert($data);
    }
    
    public function markAsRead($id)
    {
        return $this->update($id, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function markAllAsRead($userId)
    {
        $sql = "UPDATE {$this->table} SET is_read = 1, read_at = NOW() WHERE user_id = :user_id AND is_read = 0";
        return $this->db->query($sql)->bind(':user_id', $userId)->execute();
    }
    
    public function countUnread($userId)
    {
        return $this->countBy('user_id', $userId);
    }
    
    public function getUnread($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC";
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
}
