<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'name', 'description', 'permissions'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|max_length[50]|is_unique[roles.name,id,{id}]',
        'description' => 'max_length[255]'
    ];
    
    public function getRolePermissions($roleId)
    {
        $role = $this->find($roleId);
        return $role ? json_decode($role['permissions'], true) : [];
    }
    
    public function hasPermission($roleId, $permission)
    {
        $permissions = $this->getRolePermissions($roleId);
        return in_array($permission, $permissions);
    }
}
