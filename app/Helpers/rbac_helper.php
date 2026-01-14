<?php

if (!function_exists('has_permission')) {
    /**
     * Check if current user has specific permission
     * 
     * @param string $permission
     * @return bool
     */
    function has_permission($permission)
    {
        if (!session()->get('logged_in')) {
            return false;
        }
        
        $userId = session()->get('user_id');
        if (!$userId) {
            return false;
        }
        
        $userModel = new \App\Models\UserModel();
        return $userModel->hasPermission($userId, $permission);
    }
}

if (!function_exists('user_role')) {
    /**
     * Get current user's role name
     * 
     * @return string|null
     */
    function user_role()
    {
        return session()->get('role_name') ?? session()->get('role');
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Check if current user is super admin
     * 
     * @return bool
     */
    function is_super_admin()
    {
        $role = session()->get('role_name') ?? session()->get('role');
        return $role === 'super_admin' || $role === 'Super Administrator';
    }
}

if (!function_exists('user_permissions')) {
    /**
     * Get all permissions for current user
     * 
     * @return array
     */
    function user_permissions()
    {
        if (!session()->get('logged_in')) {
            return [];
        }
        
        $userId = session()->get('user_id');
        if (!$userId) {
            return [];
        }
        
        $userModel = new \App\Models\UserModel();
        return $userModel->getUserPermissions($userId);
    }
}