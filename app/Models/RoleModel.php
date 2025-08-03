<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    public function getAllRoles()
    {
        $builder = $this->db->table('position_lookup');
        return $builder->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('level', 'DESC')
                       ->get()->getResultArray();
    }

    public function getRoleByCode($code)
    {
        $builder = $this->db->table('position_lookup');
        return $builder->where('code', $code)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->get()->getRowArray();
    }

    public function getRolePermissions($positionId)
    {
        $builder = $this->db->table('position_lookup');
        $position = $builder->where('id', $positionId)->get()->getRowArray();
        if ($position && !empty($position['permissions'])) {
            return json_decode($position['permissions'], true);
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
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $builder = $this->db->table('position_lookup');
        return $builder->insert($data);
    }

    public function updateRole($id, $data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        $data['date_modified'] = date('Y-m-d H:i:s');
        $builder = $this->db->table('position_lookup');
        return $builder->where('id', $id)->update($data);
    }

    public function softDeleteRole($id)
    {
        $builder = $this->db->table('position_lookup');
        return $builder->where('id', $id)->update([
            'is_delete' => 1, 
            'date_modified' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function hasPermission($userId, $resource, $permission = 'read')
    {
        $builder = $this->db->table('user_rel ur');
        $builder->select('pl.permissions');
        $builder->join('position_lookup pl', 'pl.id = ur.position_id');
        $builder->where('ur.user_id', $userId);
        $builder->where('ur.is_active', 1);
        $builder->where('ur.is_delete', 0);
        $builder->where('pl.is_active', 1);
        $builder->where('pl.is_delete', 0);
        $userPosition = $builder->get()->getRowArray();
        
        if (!$userPosition || empty($userPosition['permissions'])) {
            return false;
        }
        
        $permissions = json_decode($userPosition['permissions'], true);
        
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
