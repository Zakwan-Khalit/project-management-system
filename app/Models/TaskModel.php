<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    public function getTaskById($taskId)
    {
        $builder = $this->db->table('tasks');
        $builder->select('
            tasks.*, 
            projects.name as project_name,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color,
            priority_lookup.level as priority_level,
            creator_profile.first_name as creator_first_name,
            creator_profile.last_name as creator_last_name,
            owner_profile.first_name as owner_first_name,
            owner_profile.last_name as owner_last_name
        ');
        $builder->join('projects', 'projects.id = tasks.project_id AND projects.is_delete = 0');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.type = "task" AND priority_lookup.is_delete = 0', 'left');
        $builder->join('task_ownership', 'task_ownership.task_id = tasks.id AND task_ownership.is_current = 1 AND task_ownership.is_delete = 0', 'left');
        $builder->join('user_profile as creator_profile', 'creator_profile.user_id = task_ownership.created_by AND creator_profile.is_delete = 0', 'left');
        $builder->join('user_profile as owner_profile', 'owner_profile.user_id = task_ownership.owned_by AND owner_profile.is_delete = 0', 'left');
        $builder->where('tasks.id', $taskId);
        $builder->where('tasks.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    public function getTasksWithDetails($projectId = null)
    {
        $builder = $this->db->table('tasks');
        $builder->select('
            tasks.*, 
            projects.name as project_name,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            status_lookup.code as status_code,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color,
            priority_lookup.level as priority_level,
            owner_profile.first_name as owner_first_name,
            owner_profile.last_name as owner_last_name,
            owner_profile.avatar as owner_avatar
        ');
        $builder->join('projects', 'projects.id = tasks.project_id AND projects.is_delete = 0');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.type = "task" AND priority_lookup.is_delete = 0', 'left');
        $builder->join('task_ownership', 'task_ownership.task_id = tasks.id AND task_ownership.is_current = 1 AND task_ownership.is_delete = 0', 'left');
        $builder->join('user_profile as owner_profile', 'owner_profile.user_id = task_ownership.owned_by AND owner_profile.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('tasks.is_active', 1);
        
        if ($projectId) {
            $builder->where('tasks.project_id', $projectId);
        }
        
        $builder->orderBy('tasks.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    public function getKanbanTasks($projectId)
    {
        $builder = $this->db->table('tasks');
        $builder->select('
            tasks.*,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            status_lookup.code as status_code,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color,
            priority_lookup.level as priority_level,
            owner_profile.first_name as owner_first_name,
            owner_profile.last_name as owner_last_name,
            owner_profile.avatar as owner_avatar
        ');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.type = "task" AND priority_lookup.is_delete = 0', 'left');
        $builder->join('task_ownership', 'task_ownership.task_id = tasks.id AND task_ownership.is_current = 1 AND task_ownership.is_delete = 0', 'left');
        $builder->join('user_profile as owner_profile', 'owner_profile.user_id = task_ownership.owned_by AND owner_profile.is_delete = 0', 'left');
        $builder->where('tasks.project_id', $projectId);
        $builder->where('tasks.is_delete', 0);
        $builder->where('tasks.is_active', 1);
        $builder->orderBy('tasks.order_index', 'ASC');
        $builder->orderBy('tasks.created_at', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function getUserTasks($userId, $limit = null)
    {
        $builder = $this->db->table('tasks');
        $builder->select('
            tasks.*,
            projects.name as project_name,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            status_lookup.code as status_code,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color,
            priority_lookup.level as priority_level
        ');
        $builder->join('projects', 'projects.id = tasks.project_id AND projects.is_delete = 0');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.type = "task" AND priority_lookup.is_delete = 0', 'left');
        $builder->join('task_ownership', 'task_ownership.task_id = tasks.id AND task_ownership.is_current = 1 AND task_ownership.is_delete = 0', 'left');
        $builder->where('task_ownership.owned_by', $userId);
        $builder->where('tasks.is_delete', 0);
        $builder->where('tasks.is_active', 1);
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        $builder->orderBy('tasks.due_date', 'ASC');
        $builder->orderBy('priority_lookup.level', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    // Task Management Functions
    public function createTask($taskData, $statusId = null, $priorityId = null, $ownedBy = null, $createdBy = null)
    {
        $taskData['created_at'] = date('Y-m-d H:i:s');
        $taskData['updated_at'] = date('Y-m-d H:i:s');
        $taskData['is_active'] = 1;
        $taskData['is_delete'] = 0;
        
        $builder = $this->db->table('tasks');
        $builder->insert($taskData);
        $taskId = $this->db->insertID();
        
        if ($taskId) {
            // Set task status
            if ($statusId) {
                $this->setTaskStatus($taskId, $statusId, $createdBy);
            }
            
            // Set task priority
            if ($priorityId) {
                $this->setTaskPriority($taskId, $priorityId, $createdBy);
            }
            
            // Set task ownership
            if ($ownedBy || $createdBy) {
                $this->setTaskOwnership($taskId, $ownedBy ?: $createdBy, $createdBy);
            }
        }
        
        return $taskId;
    }
    
    public function updateTask($taskId, $taskData)
    {
        $taskData['updated_at'] = date('Y-m-d H:i:s');
        
        $builder = $this->db->table('tasks');
        $builder->where('id', $taskId);
        return $builder->update($taskData);
    }
    
    public function deleteTask($taskId, $deletedBy = null)
    {
        $builder = $this->db->table('tasks');
        $builder->where('id', $taskId);
        return $builder->update([
            'is_delete' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    // Task Status Functions
    public function setTaskStatus($taskId, $statusId, $changedBy = null)
    {
        // Deactivate current status
        $builder = $this->db->table('task_status');
        $builder->where('task_id', $taskId);
        $builder->update(['is_current' => 0]);
        
        // Set new status
        $builder = $this->db->table('task_status');
        return $builder->insert([
            'task_id' => $taskId,
            'status_id' => $statusId,
            'changed_by' => $changedBy,
            'changed_at' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'is_delete' => 0
        ]);
    }
    
    public function getTaskStatus($taskId)
    {
        $builder = $this->db->table('task_status');
        $builder->select('task_status.*, status_lookup.code, status_lookup.name, status_lookup.color');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id');
        $builder->where('task_status.task_id', $taskId);
        $builder->where('task_status.is_current', 1);
        $builder->where('task_status.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    // Task Priority Functions
    public function setTaskPriority($taskId, $priorityId, $changedBy = null)
    {
        // Deactivate current priority
        $builder = $this->db->table('task_priority');
        $builder->where('task_id', $taskId);
        $builder->update(['is_current' => 0]);
        
        // Set new priority
        $builder = $this->db->table('task_priority');
        return $builder->insert([
            'task_id' => $taskId,
            'priority_id' => $priorityId,
            'changed_by' => $changedBy,
            'changed_at' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'is_delete' => 0
        ]);
    }
    
    public function getTaskPriority($taskId)
    {
        $builder = $this->db->table('task_priority');
        $builder->select('task_priority.*, priority_lookup.code, priority_lookup.name, priority_lookup.color, priority_lookup.level');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id');
        $builder->where('task_priority.task_id', $taskId);
        $builder->where('task_priority.is_current', 1);
        $builder->where('task_priority.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    // Task Ownership Functions
    public function setTaskOwnership($taskId, $ownedBy, $createdBy = null)
    {
        // Deactivate current ownership
        $builder = $this->db->table('task_ownership');
        $builder->where('task_id', $taskId);
        $builder->update(['is_current' => 0]);
        
        // Set new ownership
        $builder = $this->db->table('task_ownership');
        return $builder->insert([
            'task_id' => $taskId,
            'owned_by' => $ownedBy,
            'created_by' => $createdBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'is_delete' => 0
        ]);
    }
    
    public function getTaskOwnership($taskId)
    {
        $builder = $this->db->table('task_ownership');
        $builder->select('
            task_ownership.*,
            owner_profile.first_name as owner_first_name,
            owner_profile.last_name as owner_last_name,
            owner_profile.avatar as owner_avatar,
            creator_profile.first_name as creator_first_name,
            creator_profile.last_name as creator_last_name
        ');
        $builder->join('user_profile as owner_profile', 'owner_profile.user_id = task_ownership.owned_by AND owner_profile.is_delete = 0', 'left');
        $builder->join('user_profile as creator_profile', 'creator_profile.user_id = task_ownership.created_by AND creator_profile.is_delete = 0', 'left');
        $builder->where('task_ownership.task_id', $taskId);
        $builder->where('task_ownership.is_current', 1);
        $builder->where('task_ownership.is_delete', 0);
        return $builder->get()->getRowArray();
    }
    
    // Task Statistics
    public function getTaskStats($projectId = null, $userId = null)
    {
        $builder = $this->db->table('tasks t');
        $builder->select('
            COUNT(DISTINCT t.id) as total_tasks,
            COUNT(DISTINCT CASE WHEN sl.code = "completed" THEN t.id END) as completed_tasks,
            COUNT(DISTINCT CASE WHEN sl.code = "in_progress" THEN t.id END) as in_progress_tasks,
            COUNT(DISTINCT CASE WHEN sl.code = "pending" THEN t.id END) as pending_tasks,
            AVG(t.progress) as avg_progress
        ');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.type = "task" AND sl.is_delete = 0', 'left');
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        
        if ($projectId) {
            $builder->where('t.project_id', $projectId);
        }
        
        if ($userId) {
            $builder->join('task_ownership to', 'to.task_id = t.id AND to.is_current = 1 AND to.is_delete = 0');
            $builder->where('to.owned_by', $userId);
        }
        
        return $builder->get()->getRowArray();
    }
    
    // Search Functions
    public function searchTasks($search = '', $projectId = null, $limit = 10)
    {
        $builder = $this->db->table('tasks');
        $builder->select('
            tasks.*,
            projects.name as project_name,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color
        ');
        $builder->join('projects', 'projects.id = tasks.project_id AND projects.is_delete = 0');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.type = "task" AND priority_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('tasks.is_active', 1);
        
        if ($projectId) {
            $builder->where('tasks.project_id', $projectId);
        }
        
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('tasks.title', $search);
            $builder->orLike('tasks.description', $search);
            $builder->groupEnd();
        }
        
        $builder->limit($limit);
        $builder->orderBy('tasks.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    // Lookup Functions
    public function getTaskStatuses()
    {
        $builder = $this->db->table('status_lookup');
        $builder->where('type', 'task');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('order_index', 'ASC');
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    public function getTaskPriorities()
    {
        $builder = $this->db->table('priority_lookup');
        $builder->where('type', 'task');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('level', 'DESC');
        $builder->orderBy('name', 'ASC');
        return $builder->get()->getResultArray();
    }
}
