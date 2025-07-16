<?php

namespace App\Models;

use CodeIgniter\Model;

class PriorityLookupModel extends Model
{
    public function getPrioritiesByType($type)
    {
        $builder = $this->db->table('priority_lookup');
        return $builder->where('type', $type)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('level', 'ASC')
                       ->orderBy('order_index', 'ASC')
                       ->get()->getResultArray();
    }

    public function getActivePriorities()
    {
        $builder = $this->db->table('priority_lookup');
        return $builder->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->orderBy('type', 'ASC')
                       ->orderBy('level', 'ASC')
                       ->get()->getResultArray();
    }

    public function getPriorityByTypeAndCode($type, $code)
    {
        $builder = $this->db->table('priority_lookup');
        return $builder->where('type', $type)
                       ->where('code', $code)
                       ->where('is_active', 1)
                       ->where('is_delete', 0)
                       ->get()->getRowArray();
    }

    public function createPriority($data)
    {
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        $builder = $this->db->table('priority_lookup');
        return $builder->insert($data);
    }

    public function updatePriority($id, $data)
    {
        $builder = $this->db->table('priority_lookup');
        return $builder->where('id', $id)->update($data);
    }

    public function softDeletePriority($id)
    {
        $builder = $this->db->table('priority_lookup');
        return $builder->where('id', $id)->update(['is_delete' => 1]);
    }
}
