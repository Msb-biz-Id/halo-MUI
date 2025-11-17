<?php

namespace App\Models;

use Core\Model;

/**
 * Media Model
 */
class Media extends Model
{
    protected $table = 'media';
    
    public function uploadMedia($data)
    {
        $data['uploaded_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
    
    public function getByUser($userId)
    {
        return $this->findBy('uploaded_by', $userId);
    }
    
    public function getByType($mimeType)
    {
        $sql = "SELECT * FROM {$this->table} WHERE mime_type LIKE :mime_type ORDER BY uploaded_at DESC";
        return $this->db->query($sql)->bind(':mime_type', "{$mimeType}%")->fetchAll();
    }
}
