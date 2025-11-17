<?php

namespace App\Models;

use Core\Model;

/**
 * CertificateApplication Model
 */
class CertificateApplication extends Model
{
    protected $table = 'certificate_applications';
    
    public function generateTicketNumber()
    {
        $prefix = 'CERT';
        $year = date('Y');
        $month = date('m');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}{$month}-{$random}";
    }
    
    public function createApplication($data)
    {
        $data['ticket_number'] = $this->generateTicketNumber();
        $data['submitted_at'] = date('Y-m-d H:i:s');
        
        if (isset($data['documents']) && is_array($data['documents'])) {
            $data['documents'] = json_encode($data['documents']);
        }
        
        return $this->insert($data);
    }
    
    public function getByTicket($ticketNumber)
    {
        $sql = "SELECT ca.*, u.full_name as applicant_name, u.email as applicant_email,
                admin.full_name as assigned_admin_name
                FROM {$this->table} ca
                LEFT JOIN users u ON ca.user_id = u.id
                LEFT JOIN users admin ON ca.assigned_to = admin.id
                WHERE ca.ticket_number = :ticket_number";
        
        $result = $this->db->query($sql)->bind(':ticket_number', $ticketNumber)->fetch();
        
        if ($result && !empty($result['documents'])) {
            $result['documents'] = json_decode($result['documents'], true);
        }
        
        return $result;
    }
    
    public function getByUser($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY submitted_at DESC";
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    public function getByStatus($status)
    {
        return $this->findBy('status', $status);
    }
    
    public function getAssignedTo($adminId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE assigned_to = :admin_id ORDER BY priority DESC, submitted_at ASC";
        return $this->db->query($sql)->bind(':admin_id', $adminId)->fetchAll();
    }
    
    public function updateStatus($id, $status, $reviewerId = null, $notes = null)
    {
        $data = [
            'status' => $status,
            'reviewed_at' => date('Y-m-d H:i:s')
        ];
        
        if ($reviewerId) {
            $data['reviewer_id'] = $reviewerId;
        }
        
        if ($notes) {
            $data['notes'] = $notes;
        }
        
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($id, $data);
    }
    
    public function assignToAdmin($id, $adminId)
    {
        return $this->update($id, ['assigned_to' => $adminId]);
    }
    
    public function countByStatus($status)
    {
        return $this->countBy('status', $status);
    }
    
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_review' THEN 1 ELSE 0 END) as in_review,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM {$this->table}";
        
        return $this->db->query($sql)->fetch();
    }
}
