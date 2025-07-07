<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    
    public function getProjectById($projectId)
    {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color,
            priority_lookup.level as priority_level
        ');
        $builder->join('project_status', 'project_status.project_id = projects.id AND project_status.is_current = 1 AND project_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = project_status.status_id AND status_lookup.is_delete = 0', 'left');
        $builder->join('project_priority', 'project_priority.project_id = projects.id AND project_priority.is_current = 1 AND project_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = project_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('projects.id', $projectId);
        $builder->where('projects.is_delete', 0);
        
        $result = $builder->get()->getRowArray();
        return $result;
    }
    
    public function getAllProjects()
    {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color
        ');
        $builder->join('project_status', 'project_status.project_id = projects.id AND project_status.is_current = 1 AND project_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = project_status.status_id AND status_lookup.is_delete = 0', 'left');
        $builder->join('project_priority', 'project_priority.project_id = projects.id AND project_priority.is_current = 1 AND project_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = project_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('projects.is_delete', 0);
        $builder->where('projects.is_active', 1);
        $builder->orderBy('projects.created_at', 'DESC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    public function getUserProjects($userId)
    {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            project_members.role as user_role,
            project_members.joined_at,
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color
        ');
        $builder->join('project_members', 'project_members.project_id = projects.id AND project_members.is_active = 1 AND project_members.is_delete = 0');
        $builder->join('project_status', 'project_status.project_id = projects.id AND project_status.is_current = 1 AND project_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = project_status.status_id AND status_lookup.is_delete = 0', 'left');
        $builder->join('project_priority', 'project_priority.project_id = projects.id AND project_priority.is_current = 1 AND project_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = project_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('project_members.user_id', $userId);
        $builder->where('projects.is_delete', 0);
        $builder->where('projects.is_active', 1);
        $builder->orderBy('projects.created_at', 'DESC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    public function getProjectStats($projectId)
    {
        // Get task statistics
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
        $builder->where('t.project_id', $projectId);
        $builder->where('t.is_delete', 0);
        $builder->where('t.is_active', 1);
        $taskStats = $builder->get()->getRowArray();
        
        // Get member count
        $builder = $this->db->table('project_members');
        $builder->selectCount('*', 'member_count');
        $builder->where('project_id', $projectId);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $memberCount = $builder->get()->getRowArray();
        
        return array_merge($taskStats ?: [], $memberCount ?: []);
    }
    
    public function updateProgress($projectId)
    {
        $builder = $this->db->table('tasks');
        $builder->selectAvg('progress', 'avg_progress');
        $builder->where('project_id', $projectId);
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $avgProgress = $builder->get()->getRowArray();
        
        $progress = isset($avgProgress['avg_progress']) ? round($avgProgress['avg_progress'], 2) : 0.00;
        
        $builder = $this->db->table('projects');
        $builder->where('id', $projectId);
        $result = $builder->update(['progress' => $progress]);
        return $result;
    }
    
    public function userHasAccess($userId, $projectId)
    {
        $builder = $this->db->table('project_members');
        $builder->selectCount('*', 'has_access');
        $builder->where('project_id', $projectId);
        $builder->where('user_id', $userId);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $result = $builder->get()->getRowArray();
        
        return ($result['has_access'] > 0);
    }
    
    public function getProjectClient($projectId)
    {
        $builder = $this->db->table('project_client');
        $builder->select('*');
        $builder->where('project_id', $projectId);
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('created_at', 'DESC');
        $builder->limit(1);
        $result = $builder->get()->getRowArray();
        
        return $result;
    }
    
    public function searchProjects($search = '', $userId = null, $limit = 10)
    {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            status_lookup.name as status_name,
            status_lookup.color as status_color,
            priority_lookup.name as priority_name,
            priority_lookup.color as priority_color
        ');
        $builder->join('project_status', 'project_status.project_id = projects.id AND project_status.is_current = 1 AND project_status.is_delete = 0', 'left');
        $builder->join('status_lookup', 'status_lookup.id = project_status.status_id AND status_lookup.is_delete = 0', 'left');
        $builder->join('project_priority', 'project_priority.project_id = projects.id AND project_priority.is_current = 1 AND project_priority.is_delete = 0', 'left');
        $builder->join('priority_lookup', 'priority_lookup.id = project_priority.priority_id AND priority_lookup.is_delete = 0', 'left');
        $builder->where('projects.is_delete', 0);
        $builder->where('projects.is_active', 1);
            
        if ($userId) {
            $builder->join('project_members', 'project_members.project_id = projects.id AND project_members.is_active = 1 AND project_members.is_delete = 0');
            $builder->where('project_members.user_id', $userId);
        }
        
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('projects.name', $search);
            $builder->orLike('projects.description', $search);
            $builder->orLike('projects.code', $search);
            $builder->groupEnd();
        }
        
        $builder->limit($limit);
        $builder->orderBy('projects.created_at', 'DESC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    // Project Management Functions
    public function createProject($data)
    {
        $builder = $this->db->table('projects');
        $result = $builder->insert($data);
        return $result;
    }
    
    public function updateProject($projectId, $data)
    {
        $builder = $this->db->table('projects');
        $builder->where('id', $projectId);
        $result = $builder->update($data);
        return $result;
    }
    
    public function deleteProject($projectId)
    {
        $builder = $this->db->table('projects');
        $builder->where('id', $projectId);
        $result = $builder->update(['is_delete' => 1]);
        return $result;
    }
    
    // Project Members Functions
    public function addProjectMember($projectId, $userId, $role = 'member', $assignedBy = null)
    {
        // Check if already a member
        $builder = $this->db->table('project_members');
        $builder->where('project_id', $projectId);
        $builder->where('user_id', $userId);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $existing = $builder->get()->getRowArray();
        
        if ($existing) {
            return false;
        }
        
        $builder = $this->db->table('project_members');
        $result = $builder->insert([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role' => $role,
            'assigned_by' => $assignedBy,
            'joined_at' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0
        ]);
        
        return $result;
    }
    
    public function removeProjectMember($projectId, $userId)
    {
        $builder = $this->db->table('project_members');
        $builder->where('project_id', $projectId);
        $builder->where('user_id', $userId);
        $result = $builder->update([
            'is_active' => 0,
            'left_at' => date('Y-m-d H:i:s')
        ]);
        
        return $result;
    }
    
    public function getProjectMembers($projectId)
    {
        $builder = $this->db->table('project_members');
        $builder->select('
            project_members.*,
            users.email,
            user_profile.first_name,
            user_profile.last_name,
            user_profile.avatar
        ');
        $builder->join('users', 'users.id = project_members.user_id AND users.is_delete = 0');
        $builder->join('user_profile', 'user_profile.user_id = users.id AND user_profile.is_delete = 0', 'left');
        $builder->where('project_members.project_id', $projectId);
        $builder->where('project_members.is_active', 1);
        $builder->where('project_members.is_delete', 0);
        $builder->orderBy('project_members.joined_at', 'ASC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    // Project Status Functions
    public function setProjectStatus($projectId, $statusId, $changedBy = null, $notes = null)
    {
        // Mark current status as non-current
        $builder = $this->db->table('project_status');
        $builder->where('project_id', $projectId);
        $builder->where('is_current', 1);
        $builder->update([
            'is_current' => 0,
            'end_date' => date('Y-m-d H:i:s')
        ]);
        
        // Add new status
        $builder = $this->db->table('project_status');
        $result = $builder->insert([
            'project_id' => $projectId,
            'status_id' => $statusId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'start_date' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'is_active' => 1,
            'is_delete' => 0
        ]);
        
        return $result;
    }
    
    // Project Priority Functions
    public function setProjectPriority($projectId, $priorityId, $changedBy = null, $notes = null)
    {
        // Mark current priority as non-current
        $builder = $this->db->table('project_priority');
        $builder->where('project_id', $projectId);
        $builder->where('is_current', 1);
        $builder->update([
            'is_current' => 0,
            'end_date' => date('Y-m-d H:i:s')
        ]);
        
        // Add new priority
        $builder = $this->db->table('project_priority');
        $result = $builder->insert([
            'project_id' => $projectId,
            'priority_id' => $priorityId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'start_date' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'is_active' => 1,
            'is_delete' => 0
        ]);
        
        return $result;
    }
    
    // Lookup Functions
    public function getStatuses($type = 'project')
    {
        $builder = $this->db->table('status_lookup');
        $builder->where('type', $type);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $builder->orderBy('order_index', 'ASC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    public function getPriorities($type = 'project')
    {
        $builder = $this->db->table('priority_lookup');
        $builder->where('type', $type);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $builder->orderBy('level', 'ASC');
        $builder->orderBy('order_index', 'ASC');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
}
