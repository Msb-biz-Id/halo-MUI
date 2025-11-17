<?php

namespace App\Models;

use Core\Model;

/**
 * Setting Model
 */
class Setting extends Model
{
    protected $table = 'settings';
    
    public function getByKey($key)
    {
        return $this->findOneBy('key_name', $key);
    }
    
    public function getValue($key, $default = null)
    {
        $setting = $this->getByKey($key);
        return $setting ? $setting['value'] : $default;
    }
    
    public function setValue($key, $value)
    {
        $setting = $this->getByKey($key);
        
        if ($setting) {
            return $this->update($setting['id'], ['value' => $value]);
        } else {
            return $this->insert(['key_name' => $key, 'value' => $value]);
        }
    }
    
    public function getByGroup($group)
    {
        return $this->findBy('group', $group);
    }
    
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key_name']] = $setting['value'];
        }
        
        return $result;
    }
}
