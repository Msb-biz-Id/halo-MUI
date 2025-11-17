<?php

namespace App\Models;

use Core\Model;

/**
 * AuditLog Model
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';
    
    public function createLog($userId, $action, $tableName, $recordId = null, $oldValues = null, $newValues = null)
    {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($oldValues && is_array($oldValues)) {
            $data['old_values'] = json_encode($oldValues);
        }
        
        if ($newValues && is_array($newValues)) {
            $data['new_values'] = json_encode($newValues);
        }
        
        return $this->insert($data);
    }
    
    public function getByUser($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY timestamp DESC";
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    public function getByTable($tableName)
    {
        $sql = "SELECT al.*, u.username 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.table_name = :table_name 
                ORDER BY al.timestamp DESC";
        
        return $this->db->query($sql)->bind(':table_name', $tableName)->fetchAll();
    }
    
    public function getRecent($limit = 100)
    {
        $sql = "SELECT al.*, u.username 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.timestamp DESC 
                LIMIT :limit";
        
        $this->db->query($sql)->bind(':limit', $limit, \PDO::PARAM_INT);
        return $this->db->fetchAll();
    }
}
