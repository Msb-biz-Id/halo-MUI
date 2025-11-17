<?php

namespace App\Models;

use Core\Model;

/**
 * Translation Model
 */
class Translation extends Model
{
    protected $table = 'translations';
    
    public function getByLanguage($language = 'id')
    {
        $translations = $this->findBy('language', $language);
        $result = [];
        
        foreach ($translations as $translation) {
            $result[$translation['key']] = $translation['value'];
        }
        
        return $result;
    }
    
    public function getByKey($key, $language = 'id')
    {
        $sql = "SELECT * FROM {$this->table} WHERE `key` = :key AND language = :language LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':key', $key);
        $this->db->bind(':language', $language);
        return $this->db->fetch();
    }
    
    public function translate($key, $language = 'id', $default = null)
    {
        $translation = $this->getByKey($key, $language);
        return $translation ? $translation['value'] : ($default ?? $key);
    }
    
    public function setTranslation($key, $value, $language = 'id', $group = 'general')
    {
        $translation = $this->getByKey($key, $language);
        
        if ($translation) {
            return $this->update($translation['id'], ['value' => $value]);
        } else {
            return $this->insert([
                'key' => $key,
                'value' => $value,
                'language' => $language,
                'group' => $group
            ]);
        }
    }
}
