<?php

namespace App\Models;

use Core\Model;

/**
 * Role Model
 * Handles role management
 */
class Role extends Model
{
    protected $table = 'roles';
    
    /**
     * Get role with permissions
     * 
     * @param int $id
     * @return mixed
     */
    public function getRoleWithPermissions($id)
    {
        $role = $this->findById($id);
        
        if ($role && !empty($role['permissions'])) {
            $role['permissions'] = json_decode($role['permissions'], true);
        }
        
        return $role;
    }
    
    /**
     * Get role by name
     * 
     * @param string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->findOneBy('name', $name);
    }
    
    /**
     * Create role with permissions
     * 
     * @param array $data
     * @return int
     */
    public function createWithPermissions($data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        
        return $this->insert($data);
    }
    
    /**
     * Update role with permissions
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateWithPermissions($id, $data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Check if role has permission
     * 
     * @param int $roleId
     * @param string $permission
     * @return bool
     */
    public function hasPermission($roleId, $permission)
    {
        $role = $this->getRoleWithPermissions($roleId);
        
        if (!$role) {
            return false;
        }
        
        $permissions = $role['permissions'];
        
        // Superadmin has all permissions
        if (isset($permissions['all_permissions']) && $permissions['all_permissions'] === true) {
            return true;
        }
        
        // Check in features array
        if (isset($permissions['features']) && in_array($permission, $permissions['features'])) {
            return true;
        }
        
        // Check in specific permission groups
        foreach ($permissions as $group => $items) {
            if (is_array($items)) {
                if (isset($items[$permission]) && $items[$permission] === true) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
