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
    
    public function getDashboardTaskStats($userId)
    {
        $builder = $this->db->table('tasks t');
        $builder->select('
            COUNT(*) as total_tasks,
            COUNT(CASE WHEN sl.code = "completed" THEN 1 END) as completed_tasks,
            COUNT(CASE WHEN sl.code = "in_progress" THEN 1 END) as in_progress_tasks,
            COUNT(CASE WHEN sl.code = "pending" THEN 1 END) as pending_tasks,
            COUNT(CASE WHEN t.due_date < CURDATE() AND sl.code != "completed" THEN 1 END) as overdue_tasks
        ');
        $builder->join('task_ownership to', 'to.task_id = t.id AND to.is_current = 1 AND to.is_delete = 0', 'left');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.type = "task" AND sl.is_delete = 0', 'left');
        $builder->where('to.owned_by', $userId);
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        
        $result = $builder->get()->getRowArray();
        return $result ?: [
            'total_tasks' => 0,
            'completed_tasks' => 0,
            'in_progress_tasks' => 0,
            'pending_tasks' => 0,
            'overdue_tasks' => 0
        ];
    }
    
    public function getOverdueTasks()
    {
        $builder = $this->db->table('tasks t');
        $builder->select('
            t.*,
            p.name as project_name,
            sl.name as status_name,
            sl.color as status_color,
            up.first_name,
            up.last_name,
            up.avatar
        ');
        $builder->join('projects p', 'p.id = t.project_id AND p.is_delete = 0', 'left');
        $builder->join('task_ownership to', 'to.task_id = t.id AND to.is_current = 1 AND to.is_delete = 0', 'left');
        $builder->join('user_profile up', 'up.user_id = to.owned_by AND up.is_delete = 0', 'left');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.type = "task" AND sl.is_delete = 0', 'left');
        $builder->where('t.due_date <', date('Y-m-d'));
        $builder->where('sl.code !=', 'completed');
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        $builder->orderBy('t.due_date', 'ASC');
        $builder->limit(10);
        
        return $builder->get()->getResultArray();
    }
    
    public function getTaskStatusChartData($userId)
    {
        $builder = $this->db->table('tasks t');
        $builder->select('sl.code as status, COUNT(*) as count');
        $builder->join('task_ownership to', 'to.task_id = t.id AND to.is_current = 1 AND to.is_delete = 0', 'left');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.type = "task" AND sl.is_delete = 0', 'left');
        $builder->where('to.owned_by', $userId);
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        $builder->groupBy('sl.code');
        
        return $builder->get()->getResultArray();
    }
    
    public function getTaskPriorityChartData($userId)
    {
        $builder = $this->db->table('tasks t');
        $builder->select('pl.code as priority, COUNT(*) as count');
        $builder->join('task_ownership to', 'to.task_id = t.id AND to.is_current = 1 AND to.is_delete = 0', 'left');
        $builder->join('task_priority tp', 'tp.task_id = t.id AND tp.is_current = 1 AND tp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = tp.priority_id AND pl.type = "task" AND pl.is_delete = 0', 'left');
        $builder->where('to.owned_by', $userId);
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        $builder->groupBy('pl.code');
        
        return $builder->get()->getResultArray();
    }

    // Task comment methods
    public function addTaskComment($taskId, $userId, $comment)
    {
        $data = [
            'task_id' => $taskId,
            'user_id' => $userId,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->table('task_comments')->insert($data);
    }
    
    public function getTaskComments($taskId)
    {
        $builder = $this->db->table('task_comments tc');
        $builder->select('tc.*, u.email, up.first_name, up.last_name, up.avatar');
        $builder->join('users u', 'u.id = tc.user_id', 'left');
        $builder->join('user_profile up', 'up.user_id = u.id', 'left');
        $builder->where('tc.task_id', $taskId);
        $builder->orderBy('tc.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function updateTaskPosition($taskId, $statusCode, $position)
    {
        // Get status ID from code
        $statusLookup = $this->db->table('status_lookup')
                                ->where('type', 'task')
                                ->where('code', $statusCode)
                                ->get()->getRowArray();
        
        if (!$statusLookup) {
            return false;
        }
        
        // Update task status
        $this->setTaskStatus($taskId, $statusLookup['id']);
        
        // Update task position
        return $this->updateTask($taskId, ['order_index' => $position]);
    }
    
    public function getSubTasks($parentTaskId)
    {
        // Note: Based on schema, there's no parent_task_id field in the current structure
        // This would need to be added to the tasks table if sub-tasks are required
        return [];
    }

    public function getStatistics()
    {
        $builder = $this->db->table('tasks');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        
        $total = $builder->countAllResults(false);
        
        $completedBuilder = clone $builder;
        $completed = $completedBuilder->where('status_lookup.code', 'completed')->countAllResults();
        
        $pendingBuilder = clone $builder;  
        $pending = $pendingBuilder->where('status_lookup.code', 'pending')->countAllResults();
        
        $inProgressBuilder = clone $builder;
        $in_progress = $inProgressBuilder->where('status_lookup.code', 'in_progress')->countAllResults();
        
        $reviewBuilder = clone $builder;
        $review = $reviewBuilder->where('status_lookup.code', 'review')->countAllResults();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'in_progress' => $in_progress,
            'review' => $review
        ];
    }

    // Get summary stats for tasks (by status)
    public function getTaskSummaryStats()
    {
        $builder = $this->db->table('tasks t');
        $builder->select('sl.code as status_code, COUNT(*) as count');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.is_delete = 0', 'left');
        $builder->where('t.is_delete', 0);
        $builder->groupBy('sl.code');
        $result = $builder->get()->getResultArray();
        $stats = ['total' => 0, 'completed' => 0, 'pending' => 0, 'in_progress' => 0];
        foreach ($result as $row) {
            $stats['total'] += $row['count'];
            if (isset($stats[$row['status_code']])) {
                $stats[$row['status_code']] = $row['count'];
            }
        }
        return $stats;
    }

    // Get task status distribution for reports
    public function getTaskStatusDistribution()
    {
        $builder = $this->db->table('tasks t');
        $builder->select('sl.name as status_name, COUNT(*) as count');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.is_delete = 0', 'left');
        $builder->where('t.is_delete', 0);
        $builder->groupBy('sl.name');
        $result = $builder->get()->getResultArray();
        $dist = [];
        foreach ($result as $row) {
            $dist[$row['status_name']] = $row['count'];
        }
        return $dist;
    }

    public function getMonthlyCompletionData($months = 6)
    {
        $monthlyData = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            $builder = $this->db->table('tasks');
            $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
            $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
            $builder->where('tasks.is_delete', 0);
            $builder->where('status_lookup.code', 'completed');
            $builder->where("DATE_FORMAT(tasks.updated_at, '%Y-%m')", $month);
            
            $completedInMonth = $builder->countAllResults();
            
            $monthlyData[] = [
                'month' => $monthName,
                'completed_tasks' => $completedInMonth
            ];
        }
        
        return $monthlyData;
    }

    public function getTasksByStatus($statusCode)
    {
        $builder = $this->db->table('tasks');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('status_lookup.code', $statusCode);
        return $builder->get()->getResultArray();
    }
    
    public function countTasksByStatus($statusCode)
    {
        $builder = $this->db->table('tasks');
        $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('status_lookup.code', $statusCode);
        return $builder->countAllResults();
    }
    
    public function getTasksByPriority($priorityCode)
    {
        $builder = $this->db->table('tasks');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('priority_lookup.code', $priorityCode);
        return $builder->get()->getResultArray();
    }
    
    public function countTasksByPriority($priorityCode)
    {
        $builder = $this->db->table('tasks');
        $builder->join('task_priority', 'task_priority.task_id = tasks.id AND task_priority.is_current = 1 AND task_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = task_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('tasks.is_delete', 0);
        $builder->where('priority_lookup.code', $priorityCode);
        return $builder->countAllResults();
    }
    
    public function getTasksDueInDateRange($startDate, $endDate)
    {
        $builder = $this->db->table('tasks');
        $builder->where('tasks.is_delete', 0);
        $builder->where('tasks.due_date >=', $startDate);
        $builder->where('tasks.due_date <=', $endDate);
        return $builder->get()->getResultArray();
    }
    
    public function getDailyCompletionsForPeriod($days = 30)
    {
        $dailyCompletions = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            $builder = $this->db->table('tasks');
            $builder->join('task_status', 'task_status.task_id = tasks.id AND task_status.is_current = 1 AND task_status.is_delete = 0', 'left');
            $builder->join('status_lookup', 'status_lookup.id = task_status.status_id AND status_lookup.type = "task" AND status_lookup.is_delete = 0', 'left');
            $builder->where('tasks.is_delete', 0);
            $builder->where('status_lookup.code', 'completed');
            $builder->where("DATE(tasks.updated_at)", $date);
            
            $completedCount = $builder->countAllResults();
            
            $dailyCompletions[] = [
                'date' => date('M j', strtotime($date)),
                'completed' => $completedCount
            ];
        }
        
        return $dailyCompletions;
    }
    
    public function getUserProductivityStats($limit = 10)
    {
        $builder = $this->db->table('tasks t');
        $builder->select('user_profile.first_name, user_profile.last_name, users.email, COUNT(*) as completed_tasks');
        $builder->join('task_assignment', 'task_assignment.task_id = t.id AND task_assignment.is_current = 1 AND task_assignment.is_delete = 0', 'inner');
        $builder->join('users', 'users.id = task_assignment.user_id AND users.is_delete = 0', 'inner');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->join('task_status ts', 'ts.task_id = t.id AND ts.is_current = 1 AND ts.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.type = "task" AND sl.is_delete = 0', 'left');
        $builder->where('t.is_delete', 0);
        $builder->where('sl.code', 'completed');
        $builder->groupBy('task_assignment.user_id');
        $builder->orderBy('completed_tasks', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }
}
