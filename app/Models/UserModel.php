<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'email', 'password', 'first_name', 'last_name', 
        'avatar', 'phone', 'role', 'is_active', 'is_delete', 'last_login'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'first_name' => 'required|alpha_space|max_length[50]',
        'last_name' => 'required|alpha_space|max_length[50]',
        'role' => 'required|in_list[admin,manager,developer,designer,client]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already exists'
        ]
    ];
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    public function getUsersWithRoles()
    {
        return $this->select('users.*, roles.name as role_name')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('users.is_delete', 0)
                    ->orderBy('users.created_at', 'DESC')
                    ->findAll();
    }
    
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('is_delete', 0)
                    ->first();
    }
    
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
    
    public function getProjectMembers($projectId)
    {
        return $this->select('users.*, project_members.role as project_role')
                    ->join('project_members', 'project_members.user_id = users.id')
                    ->where('project_members.project_id', $projectId)
                    ->where('users.is_delete', 0)
                    ->findAll();
    }
}
