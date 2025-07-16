<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    public function getAllRoles()
    {
        $builder = $this->db->table('user_role_lookup');
        return $builder->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('level', 'DESC')
                       ->get()->getResultArray();
    }

    public function getRoleByCode($code)
    {
        $builder = $this->db->table('user_role_lookup');
        return $builder->where('code', $code)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->get()->getRowArray();
    }

    public function getRolePermissions($roleId)
    {
        $builder = $this->db->table('user_role_lookup');
        $role = $builder->where('id', $roleId)->get()->getRowArray();
        if ($role && !empty($role['permissions'])) {
            return json_decode($role['permissions'], true);
        }
        return [];
    }

    public function createRole($data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        $builder = $this->db->table('user_role_lookup');
        return $builder->insert($data);
    }

    public function updateRole($id, $data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        $builder = $this->db->table('user_role_lookup');
        return $builder->where('id', $id)->update($data);
    }

    public function softDeleteRole($id)
    {
        $builder = $this->db->table('user_role_lookup');
        return $builder->where('id', $id)->update(['is_delete' => 1]);
    }
    public function hasPermission($userId, $resource, $permission = 'read')
    {
        $builder = $this->db->table('user_role ur');
        $builder->select('url.permissions');
        $builder->join('user_role_lookup url', 'url.id = ur.role_id');
        $builder->where('ur.user_id', $userId);
        $builder->where('ur.is_active', 1);
        $builder->where('ur.is_delete', 0);
        $builder->where('url.is_active', 1);
        $builder->where('url.is_delete', 0);
        $userRole = $builder->get()->getRowArray();
        if (!$userRole || empty($userRole['permissions'])) {
            return false;
        }
        $permissions = json_decode($userRole['permissions'], true);
        // Check for superadmin access
        if (isset($permissions['all']) && $permissions['all'] === true) {
            return true;
        }
        // Check specific resource permission
        if (isset($permissions[$resource])) {
            if (is_array($permissions[$resource])) {
                return in_array($permission, $permissions[$resource]);
            }
            return $permissions[$resource] === true;
        }
        return false;
    }
}
