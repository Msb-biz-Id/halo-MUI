<?php

namespace App\Models;

use Core\Model;

/**
 * WhatsappUser Model
 */
class WhatsappUser extends Model
{
    protected $table = 'whatsapp_users';
    
    public function getByWhatsappId($whatsappId)
    {
        $user = $this->findOneBy('whatsapp_id', $whatsappId);
        
        if ($user && !empty($user['gemini_history'])) {
            $user['gemini_history'] = json_decode($user['gemini_history'], true);
        }
        
        return $user;
    }
    
    public function createOrUpdate($whatsappId, $data = [])
    {
        $user = $this->getByWhatsappId($whatsappId);
        
        if (!$user) {
            $data['whatsapp_id'] = $whatsappId;
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->insert($data);
        } else {
            return $this->update($user['id'], $data);
        }
    }
    
    public function updateHistory($whatsappId, $history)
    {
        $user = $this->getByWhatsappId($whatsappId);
        
        if ($user) {
            return $this->update($user['id'], [
                'gemini_history' => json_encode($history),
                'last_interaction' => date('Y-m-d H:i:s')
            ]);
        }
        
        return false;
    }
    
    public function clearHistory($whatsappId)
    {
        $user = $this->getByWhatsappId($whatsappId);
        
        if ($user) {
            return $this->update($user['id'], ['gemini_history' => null]);
        }
        
        return false;
    }
}
