<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'project_id', 'assigned_to', 'created_by', 'title', 'description',
        'status', 'priority', 'due_date', 'estimated_hours', 'actual_hours',
        'progress', 'position', 'parent_task_id', 'is_delete'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'project_id' => 'required|integer',
        'created_by' => 'required|integer',
        'title' => 'required|max_length[255]',
        'status' => 'in_list[pending,in_progress,review,completed]',
        'priority' => 'in_list[low,medium,high,critical]',
        'progress' => 'integer|greater_than_equal_to[0]|less_than_equal_to[100]'
    ];
    
    public function getTasksWithDetails($projectId = null)
    {
        $builder = $this->select('
            tasks.*, 
            projects.name as project_name,
            assigned_user.first_name as assigned_first_name,
            assigned_user.last_name as assigned_last_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('projects', 'projects.id = tasks.project_id')
        ->join('users as assigned_user', 'assigned_user.id = tasks.assigned_to', 'left')
        ->join('users as creator', 'creator.id = tasks.created_by')
        ->where('tasks.is_delete', 0);
        
        if ($projectId) {
            $builder->where('tasks.project_id', $projectId);
        }
        
        return $builder->orderBy('tasks.position', 'ASC')
                      ->orderBy('tasks.created_at', 'DESC')
                      ->findAll();
    }
    
    public function getKanbanTasks($projectId)
    {
        $tasks = $this->getTasksWithDetails($projectId);
        
        $kanban = [
            'pending' => [],
            'in_progress' => [],
            'review' => [],
            'completed' => []
        ];
        
        foreach ($tasks as $task) {
            $kanban[$task['status']][] = $task;
        }
        
        return $kanban;
    }
    
    public function getUserTasks($userId)
    {
        return $this->select('
            tasks.*, 
            projects.name as project_name,
            assigned_user.first_name as assigned_first_name,
            assigned_user.last_name as assigned_last_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('projects', 'projects.id = tasks.project_id')
        ->join('users as assigned_user', 'assigned_user.id = tasks.assigned_to', 'left')
        ->join('users as creator', 'creator.id = tasks.created_by')
        ->where('tasks.is_delete', 0)
        ->where('tasks.assigned_to', $userId)
        ->orderBy('tasks.position', 'ASC')
        ->orderBy('tasks.created_at', 'DESC')
        ->findAll();
    }
    
    public function getOverdueTasks()
    {
        return $this->select('
            tasks.*, 
            projects.name as project_name,
            assigned_user.first_name as assigned_first_name,
            assigned_user.last_name as assigned_last_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('projects', 'projects.id = tasks.project_id')
        ->join('users as assigned_user', 'assigned_user.id = tasks.assigned_to', 'left')
        ->join('users as creator', 'creator.id = tasks.created_by')
        ->where('tasks.is_delete', 0)
        ->where('tasks.due_date <', date('Y-m-d'))
        ->where('tasks.status !=', 'completed')
        ->orderBy('tasks.due_date', 'ASC')
        ->findAll();
    }
    
    public function updateTaskPosition($taskId, $newStatus, $newPosition)
    {
        return $this->update($taskId, [
            'status' => $newStatus,
            'position' => $newPosition
        ]);
    }
    
    public function getTaskComments($taskId)
    {
        $db = \Config\Database::connect();
        
        return $db->query("
            SELECT 
                tc.*,
                u.first_name,
                u.last_name,
                u.avatar
            FROM task_comments tc
            JOIN users u ON u.id = tc.user_id
            WHERE tc.task_id = ? AND tc.is_delete = 0
            ORDER BY tc.created_at ASC
        ", [$taskId])->getResultArray();
    }
    
    public function getSubTasks($parentTaskId)
    {
        return $this->getTasksWithDetails()
                    ->where('tasks.parent_task_id', $parentTaskId)
                    ->findAll();
    }
}
