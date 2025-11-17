<?php

namespace App\Models;

use Core\Model;

/**
 * Word Blacklist Model
 * Manage blacklisted words for content moderation
 */
class WordBlacklist extends Model
{
    protected $table = 'word_blacklist';
    
    /**
     * Get all active blacklisted words
     */
    public function getActiveWords()
    {
        return $this->findBy(['is_active' => 1]);
    }
    
    /**
     * Get words by severity
     */
    public function getWordsBySeverity($severity)
    {
        return $this->findBy([
            'severity' => $severity,
            'is_active' => 1
        ]);
    }
    
    /**
     * Get words by action
     */
    public function getWordsByAction($action)
    {
        return $this->findBy([
            'action' => $action,
            'is_active' => 1
        ]);
    }
    
    /**
     * Check if content contains blacklisted words
     * 
     * @param string $content Content to check
     * @return array ['has_blacklist' => bool, 'detected_words' => array, 'max_severity' => string, 'action' => string]
     */
    public function checkContent($content)
    {
        $activeWords = $this->getActiveWords();
        $detectedWords = [];
        $maxSeverity = 'low';
        $requiredAction = 'flag';
        
        // Case sensitivity setting
        $caseSensitive = $this->getSetting('blacklist_check_case_sensitive', '0') === '1';
        $contentToCheck = $caseSensitive ? $content : strtolower($content);
        
        foreach ($activeWords as $blacklist) {
            $word = $caseSensitive ? $blacklist['word'] : strtolower($blacklist['word']);
            $found = false;
            
            switch ($blacklist['type']) {
                case 'exact':
                    // Exact word match with word boundaries
                    $pattern = '/\b' . preg_quote($word, '/') . '\b/';
                    $found = preg_match($pattern, $contentToCheck);
                    break;
                    
                case 'partial':
                    // Contains the word
                    $found = strpos($contentToCheck, $word) !== false;
                    break;
                    
                case 'regex':
                    // Regex pattern
                    try {
                        $found = preg_match('/' . $word . '/', $contentToCheck);
                    } catch (\Exception $e) {
                        // Invalid regex, treat as partial
                        $found = strpos($contentToCheck, $word) !== false;
                    }
                    break;
            }
            
            if ($found) {
                $detectedWords[] = [
                    'word' => $blacklist['word'],
                    'type' => $blacklist['type'],
                    'severity' => $blacklist['severity'],
                    'action' => $blacklist['action'],
                    'description' => $blacklist['description']
                ];
                
                // Determine max severity
                $severityLevels = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                if ($severityLevels[$blacklist['severity']] > $severityLevels[$maxSeverity]) {
                    $maxSeverity = $blacklist['severity'];
                }
                
                // Determine required action (most strict)
                $actionLevels = ['flag' => 1, 'block' => 2, 'auto_reject' => 3];
                if ($actionLevels[$blacklist['action']] > $actionLevels[$requiredAction]) {
                    $requiredAction = $blacklist['action'];
                }
            }
        }
        
        return [
            'has_blacklist' => !empty($detectedWords),
            'detected_words' => $detectedWords,
            'max_severity' => $maxSeverity,
            'action' => $requiredAction,
            'count' => count($detectedWords)
        ];
    }
    
    /**
     * Log blacklist detection
     */
    public function logDetection($userId, $contentType, $contentId, $detectedWords, $content, $actionTaken)
    {
        $this->query(
            "INSERT INTO blacklist_detections 
             (user_id, content_type, content_id, detected_words, original_content, action_taken, ip_address, user_agent) 
             VALUES (:user_id, :content_type, :content_id, :detected_words, :content, :action, :ip, :ua)",
            [
                ':user_id' => $userId,
                ':content_type' => $contentType,
                ':content_id' => $contentId,
                ':detected_words' => json_encode($detectedWords),
                ':content' => $content,
                ':action' => $actionTaken,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]
        )->execute();
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get detection logs
     */
    public function getDetectionLogs($filters = [])
    {
        $sql = "SELECT bd.*, u.username, u.full_name
                FROM blacklist_detections bd
                LEFT JOIN users u ON bd.user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $sql .= " AND bd.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['content_type'])) {
            $sql .= " AND bd.content_type = :content_type";
            $params[':content_type'] = $filters['content_type'];
        }
        
        if (!empty($filters['action_taken'])) {
            $sql .= " AND bd.action_taken = :action";
            $params[':action'] = $filters['action_taken'];
        }
        
        $sql .= " ORDER BY bd.created_at DESC LIMIT 100";
        
        return $this->query($sql, $params)->fetchAll();
    }
    
    /**
     * Get detection statistics
     */
    public function getDetectionStats()
    {
        return $this->query(
            "SELECT 
                COUNT(*) as total_detections,
                COUNT(DISTINCT user_id) as unique_users,
                COUNT(CASE WHEN action_taken = 'flagged' THEN 1 END) as flagged_count,
                COUNT(CASE WHEN action_taken = 'blocked' THEN 1 END) as blocked_count,
                COUNT(CASE WHEN action_taken = 'auto_rejected' THEN 1 END) as rejected_count,
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today_count
             FROM blacklist_detections"
        )->fetch();
    }
    
    /**
     * Get setting value
     */
    private function getSetting($key, $default = '')
    {
        $setting = $this->query(
            "SELECT value FROM settings WHERE `key` = :key",
            [':key' => $key]
        )->fetch();
        
        return $setting ? $setting['value'] : $default;
    }
    
    /**
     * Add new blacklisted word
     */
    public function addWord($data)
    {
        return $this->insert([
            'word' => $data['word'],
            'type' => $data['type'] ?? 'partial',
            'severity' => $data['severity'] ?? 'medium',
            'action' => $data['action'] ?? 'block',
            'description' => $data['description'] ?? null,
            'created_by' => $data['created_by'] ?? null
        ]);
    }
    
    /**
     * Update blacklisted word
     */
    public function updateWord($id, $data)
    {
        return $this->update($id, [
            'word' => $data['word'],
            'type' => $data['type'],
            'severity' => $data['severity'],
            'action' => $data['action'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $word = $this->findById($id);
        if (!$word) return false;
        
        return $this->update($id, [
            'is_active' => $word['is_active'] ? 0 : 1
        ]);
    }
}
