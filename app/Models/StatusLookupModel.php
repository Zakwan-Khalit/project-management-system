<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusLookupModel extends Model
{
    public function getStatusesByType($type)
    {
        $builder = $this->db->table('status_lookup');
        return $builder->where('type', $type)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('order_index', 'ASC')
                       ->get()->getResultArray();
    }

    public function getActiveStatuses()
    {
        $builder = $this->db->table('status_lookup');
        return $builder->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('type', 'ASC')
                       ->orderBy('order_index', 'ASC')
                       ->get()->getResultArray();
    }

    public function getStatusByTypeAndCode($type, $code)
    {
        $builder = $this->db->table('status_lookup');
        return $builder->where('type', $type)
                       ->where('code', $code)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->get()->getRowArray();
    }

    public function createStatus($data)
    {
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        $builder = $this->db->table('status_lookup');
        return $builder->insert($data);
    }

    public function updateStatus($id, $data)
    {
        $builder = $this->db->table('status_lookup');
        return $builder->where('id', $id)->update($data);
    }

    public function softDeleteStatus($id)
    {
        $builder = $this->db->table('status_lookup');
        return $builder->where('id', $id)->update(['is_delete' => 1]);
    }
}
