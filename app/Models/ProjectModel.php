<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'name', 'description', 'status', 'priority', 'start_date', 
        'end_date', 'budget', 'created_by', 'progress'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'description' => 'max_length[1000]',
        'status' => 'in_list[planning,active,on_hold,completed,cancelled]',
        'priority' => 'in_list[low,medium,high,critical]',
        'created_by' => 'required|integer'
    ];
    
    public function getProjectsWithCreator()
    {
        return $this->select('projects.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                    ->join('users', 'users.id = projects.created_by')
                    ->orderBy('projects.created_at', 'DESC')
                    ->findAll();
    }
    
    public function getUserProjects($userId)
    {
        return $this->select('projects.*, project_members.role as user_role')
                    ->join('project_members', 'project_members.project_id = projects.id')
                    ->where('project_members.user_id', $userId)
                    ->orderBy('projects.created_at', 'DESC')
                    ->findAll();
    }
    
    public function getProjectStats($projectId)
    {
        $db = \Config\Database::connect();
        
        $taskStats = $db->query("
            SELECT 
                COUNT(*) as total_tasks,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_tasks,
                COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress_tasks,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_tasks,
                AVG(progress) as avg_progress
            FROM tasks 
            WHERE project_id = ? AND is_delete = 0
        ", [$projectId])->getRowArray();
        
        $memberCount = $db->query("
            SELECT COUNT(*) as member_count
            FROM project_members 
            WHERE project_id = ?
        ", [$projectId])->getRowArray();
        
        return array_merge($taskStats, $memberCount);
    }
    
    public function updateProgress($projectId)
    {
        $db = \Config\Database::connect();
        
        $avgProgress = $db->query("
            SELECT AVG(progress) as avg_progress
            FROM tasks 
            WHERE project_id = ? AND is_delete = 0
        ", [$projectId])->getRowArray();
        
        $progress = $avgProgress['avg_progress'] ? round($avgProgress['avg_progress']) : 0;
        
        return $this->update($projectId, ['progress' => $progress]);
    }
}
