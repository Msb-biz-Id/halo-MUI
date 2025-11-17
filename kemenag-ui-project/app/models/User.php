<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * Handles user management
 */
class User extends Model
{
    protected $table = 'users';
    
    /**
     * Create new user with hashed password
     * 
     * @param array $data
     * @return int
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        if (isset($data['security_answer_1'])) {
            $data['security_answer_1'] = password_hash($data['security_answer_1'], PASSWORD_BCRYPT);
        }
        
        if (isset($data['security_answer_2'])) {
            $data['security_answer_2'] = password_hash($data['security_answer_2'], PASSWORD_BCRYPT);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }
    
    /**
     * Get user by email
     * 
     * @param string $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        return $this->findOneBy('email', $email);
    }
    
    /**
     * Get user by username
     * 
     * @param string $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return $this->findOneBy('username', $username);
    }
    
    /**
     * Get user with role
     * 
     * @param int $id
     * @return mixed
     */
    public function getUserWithRole($id)
    {
        $sql = "SELECT u.*, r.name as role_name, r.permissions 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :id";
        
        return $this->db->query($sql)->bind(':id', $id)->fetch();
    }
    
    /**
     * Verify user password
     * 
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function verifyLogin($username, $password)
    {
        $user = $this->getUserByUsername($username);
        
        if (!$user) {
            $user = $this->getUserByEmail($username);
        }
        
        if ($user && password_verify($password, $user['password'])) {
            // Check if account is locked
            if ($user['account_locked_until'] && strtotime($user['account_locked_until']) > time()) {
                return false;
            }
            
            // Check if account is active
            if (!$user['is_active']) {
                return false;
            }
            
            // Reset failed login attempts
            $this->update($user['id'], [
                'failed_login_attempts' => 0,
                'account_locked_until' => null,
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            return $user;
        }
        
        // Increment failed login attempts
        if ($user) {
            $attempts = ($user['failed_login_attempts'] ?? 0) + 1;
            $updateData = ['failed_login_attempts' => $attempts];
            
            // Lock account if max attempts reached
            if ($attempts >= 5) {
                $updateData['account_locked_until'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            }
            
            $this->update($user['id'], $updateData);
        }
        
        return false;
    }
    
    /**
     * Update password
     * 
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }
    
    /**
     * Verify email
     * 
     * @param int $userId
     * @return bool
     */
    public function verifyEmail($userId)
    {
        return $this->update($userId, ['email_verified' => 1]);
    }
    
    /**
     * Enable MFA
     * 
     * @param int $userId
     * @param string $secret
     * @return bool
     */
    public function enableMFA($userId, $secret)
    {
        return $this->update($userId, [
            'mfa_secret' => $secret,
            'mfa_enabled' => 1
        ]);
    }
    
    /**
     * Disable MFA
     * 
     * @param int $userId
     * @return bool
     */
    public function disableMFA($userId)
    {
        return $this->update($userId, [
            'mfa_secret' => null,
            'mfa_enabled' => 0
        ]);
    }
    
    /**
     * Get users by role
     * 
     * @param int $roleId
     * @return array
     */
    public function getUsersByRole($roleId)
    {
        return $this->findBy('role_id', $roleId);
    }
    
    /**
     * Get all users with roles
     * 
     * @return array
     */
    public function getAllWithRoles()
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY u.created_at DESC";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * Search users
     * 
     * @param string $term
     * @return array
     */
    public function searchUsers($term)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.username LIKE :term 
                   OR u.email LIKE :term 
                   OR u.full_name LIKE :term 
                ORDER BY u.created_at DESC";
        
        return $this->db->query($sql)->bind(':term', "%{$term}%")->fetchAll();
    }
}
