<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'user_id', 'project_id', 'task_id', 'action', 'description',
        'old_values', 'new_values'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    public function logActivity($data)
    {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['action'])) {
            log_message('error', 'ActivityLogModel::logActivity - Missing required fields: ' . json_encode($data));
            return false;
        }
        
        // Convert arrays to JSON for storage
        if (isset($data['old_values']) && is_array($data['old_values'])) {
            $data['old_values'] = json_encode($data['old_values']);
        }
        if (isset($data['new_values']) && is_array($data['new_values'])) {
            $data['new_values'] = json_encode($data['new_values']);
        }
        
        // Ensure user_id is an integer
        $data['user_id'] = (int)$data['user_id'];
        
        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'ActivityLogModel::logActivity - Insert failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getProjectActivities($projectId, $limit = 50)
    {
        return $this->select('
            activity_logs.*,
            users.first_name,
            users.last_name,
            users.avatar
        ')
        ->join('users', 'users.id = activity_logs.user_id')
        ->where('activity_logs.project_id', $projectId)
        ->orderBy('activity_logs.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }
    
    public function getUserActivities($userId, $limit = 50)
    {
        return $this->select('
            activity_logs.*,
            projects.name as project_name,
            tasks.title as task_title
        ')
        ->join('projects', 'projects.id = activity_logs.project_id', 'left')
        ->join('tasks', 'tasks.id = activity_logs.task_id', 'left')
        ->where('activity_logs.user_id', $userId)
        ->orderBy('activity_logs.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }
    
    public function getRecentActivities($limit = 20)
    {
        return $this->select('
            activity_logs.*,
            users.first_name,
            users.last_name,
            users.avatar,
            projects.name as project_name,
            tasks.title as task_title
        ')
        ->join('users', 'users.id = activity_logs.user_id')
        ->join('projects', 'projects.id = activity_logs.project_id', 'left')
        ->join('tasks', 'tasks.id = activity_logs.task_id', 'left')
        ->orderBy('activity_logs.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }
}
