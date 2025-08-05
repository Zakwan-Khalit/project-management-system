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
            user_profile.full_name, 
            user_profile.phone, 
            position_lookup.name as role_name, 
            user_rel.position_id as role_id,
            department_lookup.name as department_name,
            user_rel.department_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.email', $email);
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
        return $builder->get()->getRowArray();
    }

    public function getUserById($userId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id,
            users.email,
            users.is_active,
            users.is_delete,
            users.date_created,
            users.date_modified,
            user_profile.full_name,
            user_profile.phone,
            position_lookup.name as role_name,
            user_rel.position_id as role_id,
            department_lookup.name as department_name,
            user_rel.department_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.id', $userId);
        $builder->where('users.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    public function getUserWithPasswordById($userId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id,
            users.email,
            users.password,
            users.is_active,
            users.is_delete,
            users.date_created,
            users.date_modified,
            user_profile.full_name,
            user_profile.phone,
            position_lookup.name as role_name,
            user_rel.position_id as role_id,
            department_lookup.name as department_name,
            user_rel.department_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.id', $userId);
        $builder->where('users.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    public function updateLastLogin($userId)
    {
        $builder = $this->db->table('users');
        $builder->where('id', $userId);
        return $builder->update(['date_modified' => date('Y-m-d H:i:s')]);
    }
    
    public function getAllUsers()
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id,
            users.email,
            user_profile.full_name as name,
            user_profile.full_name, 
            position_lookup.name as role_name,
            department_lookup.name as department_name
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
        $builder->orderBy('user_profile.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function getProjectMembers($projectId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.*, 
            user_profile.full_name, 
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
            user_profile.full_name, 
            position_lookup.name as role_name,
            department_lookup.name as department_name
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
            
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('user_profile.full_name', $search);
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
        if (isset($userData['password']) && !password_get_info($userData['password'])['algo']) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        $userData['date_created'] = date('Y-m-d H:i:s');
        $userData['date_modified'] = date('Y-m-d H:i:s');
        $userData['is_active'] = 1;
        $userData['is_delete'] = 0;
        
        $builder = $this->db->table('users');
        $builder->insert($userData);
        $userId = $this->db->insertID();
        
        if ($userId && !empty($profileData)) {
            $profileData['user_id'] = $userId;
            $profileData['date_created'] = date('Y-m-d H:i:s');
            $profileData['date_modified'] = date('Y-m-d H:i:s');
            $profileData['is_active'] = 1;
            $profileData['is_delete'] = 0;
            
            $builder = $this->db->table('user_profile');
            $builder->insert($profileData);
        }
        
        return $userId;
    }
    
    public function updateUser($userId, $userData)
    {
        if (isset($userData['password']) && !password_get_info($userData['password'])['algo']) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        $userData['date_modified'] = date('Y-m-d H:i:s');
        
        $builder = $this->db->table('users');
        $builder->where('id', $userId);
        return $builder->update($userData);
    }
    
    public function updateUserProfile($userId, $profileData)
    {
        $profileData['date_modified'] = date('Y-m-d H:i:s');
        
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
            // Create new profile if not exists
            $profileData['user_id'] = $userId;
            $profileData['date_created'] = date('Y-m-d H:i:s');
            $profileData['is_delete'] = 0;
            $builder = $this->db->table('user_profile');
            return $builder->insert($profileData);
        }
    }
    
    // User Role Functions (updated for new structure using user_rel and position_lookup)
    public function assignUserRole($userId, $positionId, $departmentId, $assignedBy = null)
    {
        // Deactivate existing user_rel entries
        $builder = $this->db->table('user_rel');
        $builder->where('user_id', $userId);
        $builder->update(['is_active' => 0, 'is_delete' => 1, 'date_modified' => date('Y-m-d H:i:s')]);
        
        // Assign new position and department
        $builder = $this->db->table('user_rel');
        return $builder->insert([
            'user_id' => $userId,
            'position_id' => $positionId,
            'department_id' => $departmentId,
            'assigned_by' => $assignedBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getUserRole($userId)
    {
        $builder = $this->db->table('user_rel');
        $builder->select('
            user_rel.*, 
            position_lookup.code, 
            position_lookup.name, 
            position_lookup.description, 
            position_lookup.level,
            department_lookup.name as department_name,
            department_lookup.code as department_code
        ');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id');
        $builder->where('user_rel.user_id', $userId);
        $builder->where('user_rel.is_active', 1);
        $builder->where('user_rel.is_delete', 0);
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
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $taskStats = $builder->get()->getRowArray();

        return array_merge($projectStats ?: [], $taskStats ?: []);
    }
    
    // Lookup Functions
    public function getRoles()
    {
        $builder = $this->db->table('position_lookup');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('level', 'ASC');
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function getDepartments()
    {
        $builder = $this->db->table('department_lookup');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getTotalActiveUsers()
    {
        $builder = $this->db->table('users');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        return $builder->countAllResults();
    }

    public function getProjectUsers($projectId)
    {
        $builder = $this->db->table('project_members pm');
        $builder->select('
            users.id,
            users.email,
            user_profile.full_name as name,
            user_profile.full_name,
            pm.role as project_role,
            position_lookup.name as role_name,
            department_lookup.name as department_name
        ');
        $builder->join('users', 'users.id = pm.user_id');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('pm.project_id', $projectId);
        $builder->where('pm.is_delete', 0);
        $builder->where('pm.is_active', 1);
        $builder->where('users.is_delete', 0);
        $builder->where('users.is_active', 1);
        $builder->orderBy('user_profile.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }

    // User Management Methods for new interface
    public function getAllUsersWithDetails()
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id,
            users.email,
            users.is_active,
            users.date_created,
            users.date_modified,
            user_profile.full_name,
            user_profile.phone,
            position_lookup.name as position_name,
            department_lookup.name as department_name,
            user_rel.position_id,
            user_rel.department_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->join('position_lookup', 'position_lookup.id = user_rel.position_id AND position_lookup.is_delete = 0', 'left');
        $builder->join('department_lookup', 'department_lookup.id = user_rel.department_id AND department_lookup.is_delete = 0', 'left');
        $builder->where('users.is_delete', 0);
        $builder->orderBy('user_profile.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getUserWithDetails($userId)
    {
        $builder = $this->db->table('users');
        $builder->select('
            users.id,
            users.email,
            users.is_active,
            users.date_created,
            users.date_modified,
            user_profile.full_name,
            user_profile.phone,
            user_rel.position_id,
            user_rel.department_id
        ');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('user_rel', 'user_rel.user_id = users.id AND user_rel.is_active = 1 AND user_rel.is_delete = 0', 'left');
        $builder->where('users.id', $userId);
        $builder->where('users.is_delete', 0);
        return $builder->get()->getRowArray();
    }

    public function createUserProfile($profileData)
    {
        $builder = $this->db->table('user_profile');
        $profileData['date_created'] = date('Y-m-d H:i:s');
        $profileData['date_modified'] = date('Y-m-d H:i:s');
        $profileData['is_delete'] = 0;
        
        return $builder->insert($profileData);
    }

    public function createUserRel($relData)
    {
        $builder = $this->db->table('user_rel');
        $relData['date_created'] = date('Y-m-d H:i:s');
        $relData['date_modified'] = date('Y-m-d H:i:s');
        $relData['is_active'] = 1;
        $relData['is_delete'] = 0;
        
        return $builder->insert($relData);
    }

    public function updateUserRel($userId, $relData)
    {
        $builder = $this->db->table('user_rel');
        $relData['date_modified'] = date('Y-m-d H:i:s');
        
        return $builder->where('user_id', $userId)->where('is_delete', 0)->update($relData);
    }

    public function deleteUser($userId)
    {
        $this->db->transStart();
        
        try {
            // Soft delete user
            $this->db->table('users')
                ->where('id', $userId)
                ->update([
                    'is_delete' => 1,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);

            // Soft delete user profile
            $this->db->table('user_profile')
                ->where('user_id', $userId)
                ->update([
                    'is_delete' => 1,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);

            // Soft delete user relationships
            $this->db->table('user_rel')
                ->where('user_id', $userId)
                ->update([
                    'is_delete' => 1,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);

            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }

    public function getPositions()
    {
        $builder = $this->db->table('position_lookup');
        $builder->select('id, name, department_id');
        $builder->where('is_delete', 0);
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getPositionsByDepartment($departmentId)
    {
        $builder = $this->db->table('position_lookup');
        $builder->select('id, name');
        $builder->where('department_id', $departmentId);
        $builder->where('is_delete', 0);
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }
}
