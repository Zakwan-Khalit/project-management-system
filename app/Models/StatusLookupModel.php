<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusLookupModel extends Model
{
    protected $table = 'status_lookup';
    protected $primaryKey = 'id';
    protected $allowedFields = ['type', 'code', 'name', 'description', 'color', 'order_index', 'is_active', 'is_delete'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'type' => 'required|max_length[50]',
        'code' => 'required|max_length[50]',
        'name' => 'required|max_length[100]'
    ];
    
    public function getStatusesByType($type)
    {
        return $this->where('type', $type)
                   ->where('is_active', 1)
                   ->where('is_delete', 0)
                   ->orderBy('order_index', 'ASC')
                   ->findAll();
    }
    
    public function getActiveStatuses()
    {
        return $this->where('is_active', 1)
                   ->where('is_delete', 0)
                   ->orderBy('type', 'ASC')
                   ->orderBy('order_index', 'ASC')
                   ->findAll();
    }
    
    public function getStatusByTypeAndCode($type, $code)
    {
        return $this->where('type', $type)
                   ->where('code', $code)
                   ->where('is_active', 1)
                   ->where('is_delete', 0)
                   ->first();
    }
    
    public function createStatus($data)
    {
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        return $this->insert($data);
    }
    
    public function updateStatus($id, $data)
    {
        return $this->update($id, $data);
    }
    
    public function softDeleteStatus($id)
    {
        return $this->update($id, ['is_delete' => 1]);
    }
}
