<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public function getUserByEmail($email)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.*, 
            user_profile.first_name, 
            user_profile.last_name, 
            user_profile.avatar, 
            user_profile.phone, 
            user_profile.bio,
            user_role_lookup.name as role_name, 
            user_role.role_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_role', 'user_role.user_id = users.id AND user_role.is_active = 1 AND user_role.is_delete = 0', 'left');
        $builder->join('user_role_lookup', 'user_role_lookup.id = user_role.role_id AND user_role_lookup.is_delete = 0', 'left');
        $builder->where('users.email', $email);
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
        return $builder->get()->getRowArray();
    }

    public function getUserById($userId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.*, 
            user_profile.first_name, 
            user_profile.last_name, 
            user_profile.avatar, 
            user_profile.phone, 
            user_profile.bio,
            user_profile.address,
            user_profile.timezone,
            user_profile.language,
            user_role_lookup.name as role_name, 
            user_role.role_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_role', 'user_role.user_id = users.id AND user_role.is_active = 1 AND user_role.is_delete = 0', 'left');
        $builder->join('user_role_lookup', 'user_role_lookup.id = user_role.role_id AND user_role_lookup.is_delete = 0', 'left');
        $builder->where('users.id', $userId);
        $builder->where('users.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    public function updateLastLogin($userId)
    {
        $builder = $this->db->table('users');
        $builder->where('id', $userId);
        return $builder->update(['last_login_at' => date('Y-m-d H:i:s')]);
    }
    
    public function getAllUsers()
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.*, 
            user_profile.first_name, 
            user_profile.last_name, 
            user_profile.avatar,
            user_role_lookup.name as role_name
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_role', 'user_role.user_id = users.id AND user_role.is_active = 1 AND user_role.is_delete = 0', 'left');
        $builder->join('user_role_lookup', 'user_role_lookup.id = user_role.role_id AND user_role_lookup.is_delete = 0', 'left');
        $builder->where('users.is_delete', 0);
        $builder->orderBy('user_profile.first_name', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function getProjectMembers($projectId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.*, 
            user_profile.first_name, 
            user_profile.last_name, 
            user_profile.avatar,
            project_members.role as project_role,
            project_members.joined_at
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('project_members', 'project_members.user_id = users.id AND project_members.is_active = 1 AND project_members.is_delete = 0');
        $builder->where('project_members.project_id', $projectId);
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
        $builder->orderBy('project_members.joined_at', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function searchUsers($search = '', $limit = 10)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id, 
            users.email,
            user_profile.first_name, 
            user_profile.last_name, 
            user_profile.avatar,
            user_role_lookup.name as role_name
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_role', 'user_role.user_id = users.id AND user_role.is_active = 1 AND user_role.is_delete = 0', 'left');
        $builder->join('user_role_lookup', 'user_role_lookup.id = user_role.role_id AND user_role_lookup.is_delete = 0', 'left');
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
            
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('user_profile.first_name', $search);
            $builder->orLike('user_profile.last_name', $search);
            $builder->orLike('users.email', $search);
            $builder->groupEnd();
        }
        
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }
    
    // User Management Functions
    public function createUser($userData, $profileData = [])
    {
        // Hash password
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        $userData['created_at'] = date('Y-m-d H:i:s');
        $userData['updated_at'] = date('Y-m-d H:i:s');
        $userData['is_active'] = 1;
        $userData['is_delete'] = 0;
        
        $builder = $this->db->table('users');
        $builder->insert($userData);
        $userId = $this->db->insertID();
        
        if ($userId && !empty($profileData)) {
            $profileData['user_id'] = $userId;
            $profileData['created_at'] = date('Y-m-d H:i:s');
            $profileData['updated_at'] = date('Y-m-d H:i:s');
            $profileData['is_active'] = 1;
            $profileData['is_delete'] = 0;
            
            $builder = $this->db->table('user_profile');
            $builder->insert($profileData);
        }
        
        return $userId;
    }
    
    public function updateUser($userId, $userData)
    {
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        $builder = $this->db->table('users');
        $builder->where('id', $userId);
        return $builder->update($userData);
    }
    
    public function updateUserProfile($userId, $profileData)
    {
        $profileData['updated_at'] = date('Y-m-d H:i:s');
        
        // Check if profile exists
        $builder = $this->db->table('user_profile');
        $builder->where('user_id', $userId);
        $builder->where('is_delete', 0);
        $existing = $builder->get()->getRowArray();
        
        if ($existing) {
            $builder = $this->db->table('user_profile');
            $builder->where('user_id', $userId);
            return $builder->update($profileData);
        } else {
            $profileData['user_id'] = $userId;
            $profileData['created_at'] = date('Y-m-d H:i:s');
            $profileData['is_active'] = 1;
            $profileData['is_delete'] = 0;
            
            $builder = $this->db->table('user_profile');
            return $builder->insert($profileData);
        }
    }
    
    // User Role Functions
    public function assignUserRole($userId, $roleId, $assignedBy = null)
    {
        // Deactivate existing roles
        $builder = $this->db->table('user_role');
        $builder->where('user_id', $userId);
        $builder->update(['is_active' => 0]);
        
        // Assign new role
        $builder = $this->db->table('user_role');
        return $builder->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => $assignedBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0
        ]);
    }
    
    public function getUserRole($userId)
    {
        $builder = $this->db->table('user_role');
        $builder->select('user_role.*, user_role_lookup.code, user_role_lookup.name, user_role_lookup.description, user_role_lookup.permissions, user_role_lookup.level');
        $builder->join('user_role_lookup', 'user_role_lookup.id = user_role.role_id');
        $builder->where('user_role.user_id', $userId);
        $builder->where('user_role.is_active', 1);
        $builder->where('user_role.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    // User Statistics
    public function getUserStats($userId)
    {
        // Get user's project count
        $builder = $this->db->table('project_members');
        $builder->selectCount('*', 'project_count');
        $builder->where('user_id', $userId);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $projectStats = $builder->get()->getRowArray();

        // Get user's task count (ownership)
        $builder = $this->db->table('task_ownership');
        $builder->selectCount('task_id', 'task_count');
        $builder->where('owned_by', $userId);
        $builder->where('is_current', 1);
        $builder->where('is_delete', 0);
        $taskStats = $builder->get()->getRowArray();

        return array_merge($projectStats ?: [], $taskStats ?: []);
    }
    
    // Lookup Functions
    public function getRoles()
    {
        $builder = $this->db->table('user_role_lookup');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('level', 'ASC');
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }
}
