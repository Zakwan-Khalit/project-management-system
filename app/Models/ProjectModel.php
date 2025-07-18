<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    /**
     * Recalculate and update the progress field for a project based on completed tasks.
     * Progress = (completed tasks / total tasks) * 100
     */
    public function updateProgress($projectId)
    {
        // Count total tasks (not deleted)
        $total = $this->db->table('tasks')
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->countAllResults();

        if ($total === 0) {
            $progress = 0;
        } else {
            // Count completed tasks (status = 'completed')
            $completed = $this->db->table('tasks t')
                ->join('task_status ts', 'ts.task_id = t.id AND ts.is_active = 1 AND ts.is_delete = 0')
                ->join('status_lookup sl', "sl.id = ts.status_id AND sl.code = 'completed' AND sl.type = 'task' AND sl.is_delete = 0")
                ->where('t.project_id', $projectId)
                ->where('t.is_delete', 0)
                ->countAllResults();
            $progress = round(($completed / $total) * 100, 2);
        }

        // Update the project
        $this->db->table('projects')
            ->where('id', $projectId)
            ->update([
                'progress' => $progress,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
        return $progress;
    }
    // Check if a user has access to a project (is an active, non-deleted member)
    public function userHasAccess($userId, $projectId)
    {
        $builder = $this->db->table('project_members');
        $builder->where('user_id', $userId);
        $builder->where('project_id', $projectId);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $result = $builder->get()->getRowArray();
        return $result !== null;
    // No protected properties or constructor
    }
    // Get a single project with current status and priority
    public function getProjectById($projectId)
    {
        $builder = $this->db->table('projects p');
        $builder->select('
            p.*,
            sl.name as status_name,
            sl.color as status_color,
            pl.name as priority_name,
            pl.color as priority_color,
            pl.level as priority_level
        ');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->join('project_priority pp', 'pp.project_id = p.id AND pp.is_active = 1 AND pp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left');
        $builder->where('p.id', $projectId);
        $builder->where('p.is_delete', 0);

        return $builder->get()->getRowArray();
    }

    // Get all active projects with current status and priority
    public function getAllProjects()
    {
        $builder = $this->db->table('projects p');
        $builder->select('
            p.*,
            sl.name as status_name,
            sl.color as status_color,
            pl.name as priority_name,
            pl.color as priority_color
        ');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->join('project_priority pp', 'pp.project_id = p.id AND pp.is_active = 1 AND pp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left');
        $builder->where('p.is_delete', 0);
        $builder->where('p.is_active', 1);
        $builder->orderBy('p.date_created', 'DESC');

        return $builder->get()->getResultArray();
    }

    // Get projects for a specific user (as a member)
    public function getUserProjects($userId)
    {
        $builder = $this->db->table('projects p');
        $builder->select('
            p.*,
            pm.role as user_role,
            pm.joined_at,
            sl.name as status_name,
            sl.color as status_color,
            sl.code as status_code,
            pl.name as priority_name,
            pl.color as priority_color
        ');
        $builder->join('project_members pm', 'pm.project_id = p.id AND pm.is_active = 1 AND pm.is_delete = 0');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->join('project_priority pp', 'pp.project_id = p.id AND pp.is_active = 1 AND pp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left');
        $builder->where('pm.user_id', $userId);
        $builder->where('p.is_delete', 0);
        $builder->where('p.is_active', 1);
        $builder->orderBy('p.date_created', 'DESC');

        $projects = $builder->get()->getResultArray();
        // Ensure every project has a team_members array
        foreach ($projects as &$project) {
            $project['team_members'] = $this->getProjectMembers($project['id']) ?? [];
        }
        unset($project);
        return $projects;
    }

    // Set current status for a project
    public function setProjectStatus($projectId, $statusId, $changedBy = null, $notes = null)
    {
        // Mark current as not current
        $this->db->table('project_status')
            ->where('project_id', $projectId)
            // Removed incomplete chained method causing syntax error
            ->update(['is_active' => 0, 'end_date' => date('Y-m-d H:i:s')]);

        // Insert new status
        return $this->db->table('project_status')->insert([
            'project_id' => $projectId,
            'status_id' => $statusId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'start_date' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_active' => 1,
            'is_delete' => 0
        ]);
    }

    // Set current priority for a project
    public function setProjectPriority($projectId, $priorityId, $changedBy = null, $notes = null)
    {
        $this->db->table('project_priority')
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->update(['is_active' => 0, 'end_date' => date('Y-m-d H:i:s')]);

        return $this->db->table('project_priority')->insert([
            'project_id' => $projectId,
            'priority_id' => $priorityId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'start_date' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_active' => 1,
            'is_delete' => 0
        ]);
    }

    // Get all status options for projects
    public function getStatuses()
    {
        return $this->db->table('status_lookup')
            ->where('type', 'project')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->orderBy('order_index', 'ASC')
            ->get()->getResultArray();
    }

    // Get all priority options for projects
    public function getPriorities()
    {
        return $this->db->table('priority_lookup')
            ->where('type', 'project')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->orderBy('level', 'ASC')
            ->orderBy('order_index', 'ASC')
            ->get()->getResultArray();
    }

    // Add a member to a project
    public function addProjectMember($projectId, $userId, $role = 'member', $assignedBy = null)
    {
        // Check if already a member
        $exists = $this->db->table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->get()->getRowArray();

        if ($exists) return false;

        return $this->db->table('project_members')->insert([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role' => $role,
            'assigned_by' => $assignedBy,
            'joined_at' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0
        ]);
    }

    // Remove a member from a project
    public function removeProjectMember($projectId, $userId)
    {
        return $this->db->table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->update(['is_active' => 0, 'left_at' => date('Y-m-d H:i:s')]);
    }

    // Get all members of a project
    public function getProjectMembers($projectId)
    {
        $builder = $this->db->table('project_members pm');
        $builder->select('
            pm.*,
            u.email,
            up.first_name,
            up.last_name,
        ');
        $builder->join('users u', 'u.id = pm.user_id AND u.is_delete = 0');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->where('pm.project_id', $projectId);
        $builder->where('pm.is_active', 1);
        $builder->where('pm.is_delete', 0);
        $builder->orderBy('pm.joined_at', 'ASC');

        return $builder->get()->getResultArray();
    }

    // Soft delete a project
    public function deleteProject($projectId)
    {
        return $this->db->table('projects')
            ->where('id', $projectId)
            ->update(['is_delete' => 1]);
    }

    // Get summary stats for projects (by status)
    public function getProjectSummaryStats()
    {
        $builder = $this->db->table('projects p');
        $builder->select('sl.code as status_code, COUNT(*) as count');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->where('p.is_delete', 0);
        $builder->groupBy('sl.code');
        $result = $builder->get()->getResultArray();
        $stats = ['total' => 0, 'active' => 0, 'completed' => 0, 'on_hold' => 0, 'cancelled' => 0];
        foreach ($result as $row) {
            $stats['total'] += $row['count'];
            if (isset($stats[$row['status_code']])) {
                $stats[$row['status_code']] = $row['count'];
            }
        }
        return $stats;
    }

    // Get project status distribution for reports
    public function getProjectStatusDistribution()
    {
        $builder = $this->db->table('projects p');
        $builder->select('sl.name as status_name, COUNT(*) as count');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->where('p.is_delete', 0);
        $builder->groupBy('sl.name');
        $result = $builder->get()->getResultArray();
        $dist = [];
        foreach ($result as $row) {
            $dist[$row['status_name']] = $row['count'];
        }
        return $dist;
    }

    // Get projects with task completion rates for reports
    public function getProjectsWithTaskStats()
    {
        $builder = $this->db->table('projects p');
        $builder->select('p.*, sl.name as status_name, pl.name as priority_name');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->join('project_priority pp', 'pp.project_id = p.id AND pp.is_active = 1 AND pp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left');
        $builder->where('p.is_delete', 0);
        $projects = $builder->get()->getResultArray();
        // For each project, get task stats
        $taskModel = new \App\Models\TaskModel();
        foreach ($projects as &$project) {
            $tasks = $taskModel->getTasksWithDetails($project['id']);
            $project['total_tasks'] = count($tasks);
            $project['completed_tasks'] = count(array_filter($tasks, function($t) { return $t['status_code'] === 'completed'; }));
            $project['completion_rate'] = $project['total_tasks'] > 0 ? round(($project['completed_tasks'] / $project['total_tasks']) * 100, 2) : 0;
        }
        return $projects;
    }

    // Get all projects for AJAX (with filters)
    public function getProjectsWithDetails($userId, $filters = [])
    {
        $builder = $this->db->table('projects p');
        $builder->select('
            p.*,
            pm.role as user_role,
            pm.joined_at,
            sl.name as status_name,
            sl.color as status_color,
            sl.code as status_code,
            pl.name as priority_name,
            pl.color as priority_color,
            pl.code as priority_code
        ');
        $builder->join('project_members pm', 'pm.project_id = p.id AND pm.user_id = ' . (int)$userId . ' AND pm.is_active = 1 AND pm.is_delete = 0');
        $builder->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1 AND ps.is_delete = 0', 'left');
        $builder->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left');
        $builder->join('project_priority pp', 'pp.project_id = p.id AND pp.is_active = 1 AND pp.is_delete = 0', 'left');
        $builder->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left');
        $builder->where('p.is_delete', 0);
        $builder->where('p.is_active', 1);
        
        // Filter by status
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('sl.code', $filters['status']);
        }
        
        // Filter by priority
        if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
            $builder->where('pl.code', $filters['priority']);
        }
        
        // Filter by search
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $builder->groupStart();
            $builder->like('LOWER(p.name)', $search);
            $builder->orLike('LOWER(p.description)', $search);
            $builder->groupEnd();
        }
        
        $builder->orderBy('p.date_created', 'DESC');
        $projects = $builder->get()->getResultArray();
        
        // Add task statistics for each project
        foreach ($projects as &$project) {
            // Get task counts
            $taskBuilder = $this->db->table('tasks t');
            $taskBuilder->select('COUNT(*) as total_tasks');
            $taskBuilder->where('t.project_id', $project['id']);
            $taskBuilder->where('t.is_delete', 0);
            $taskStats = $taskBuilder->get()->getRowArray();
            
            // Get completed task counts
            $completedBuilder = $this->db->table('tasks t');
            $completedBuilder->select('COUNT(*) as completed_tasks');
            $completedBuilder->join('task_status ts', 'ts.task_id = t.id AND ts.is_active = 1 AND ts.is_delete = 0', 'left');
            $completedBuilder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.code = "completed" AND sl.is_delete = 0', 'left');
            $completedBuilder->where('t.project_id', $project['id']);
            $completedBuilder->where('t.is_delete', 0);
            $completedBuilder->where('sl.id IS NOT NULL');
            $completedStats = $completedBuilder->get()->getRowArray();
            
            // Get member count
            $memberBuilder = $this->db->table('project_members');
            $memberBuilder->select('COUNT(*) as member_count');
            $memberBuilder->where('project_id', $project['id']);
            $memberBuilder->where('is_active', 1);
            $memberBuilder->where('is_delete', 0);
            $memberStats = $memberBuilder->get()->getRowArray();
            
            $project['total_tasks'] = (int)$taskStats['total_tasks'];
            $project['completed_tasks'] = (int)$completedStats['completed_tasks'];
            $project['member_count'] = (int)$memberStats['member_count'];
            
            // Ensure we have the right field names for JavaScript
            $project['status'] = $project['status_code'] ?? 'planning';
            $project['priority'] = $project['priority_code'] ?? 'medium';
        }
        
        return $projects;
    }

    // Get stats for a single project (tasks, completed, team, days left)
    public function getProjectStats($projectId)
    {
        $db = $this->db;
        // Total tasks
        $total = $db->table('tasks')->where('project_id', $projectId)->where('is_delete', 0)->countAllResults();
        // Completed tasks
        $completed = $db->table('tasks t')
            ->join('task_status ts', 'ts.task_id = t.id AND ts.is_active = 1 AND ts.is_delete = 0', 'left')
            ->join('status_lookup sl', 'sl.id = ts.status_id AND sl.code = "completed" AND sl.is_delete = 0', 'left')
            ->where('t.project_id', $projectId)
            ->where('t.is_delete', 0)
            ->where('sl.id IS NOT NULL')
            ->countAllResults();
        // Team members
        $members = $db->table('project_members')->where('project_id', $projectId)->where('is_active', 1)->where('is_delete', 0)->countAllResults();
        // Days left
        $project = $this->getProjectById($projectId);
        $daysLeft = 0;
        if ($project && $project['end_date']) {
            $end = strtotime($project['end_date']);
            $now = strtotime(date('Y-m-d'));
            $daysLeft = ($end >= $now) ? ceil(($end - $now) / 86400) : 0;
        }
        return [
            'total_tasks' => $total,
            'completed_tasks' => $completed,
            'team_members' => $members,
            'days_left' => $daysLeft
        ];
    }

    // Progress chart data (dummy linear for now)
    public function getProgressChartData($projectId)
    {
        // Example: progress over time (simulate)
        $project = $this->getProjectById($projectId);
        $labels = [];
        $values = [];
        if ($project && $project['start_date'] && $project['end_date']) {
            $start = strtotime($project['start_date']);
            $end = strtotime($project['end_date']);
            $days = max(1, ceil(($end - $start) / 86400));
            for ($i = 0; $i <= $days; $i += max(1, floor($days / 6))) {
                $labels[] = date('Y-m-d', $start + $i * 86400);
                // Simulate progress: linear from 0 to project.progress
                $values[] = round(($project['progress'] ?? 0) * min(1, $i / $days), 2);
            }
        }
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    // Task distribution chart data
    public function getTaskDistributionData($projectId)
    {
        $db = $this->db;
        $statuses = ['todo', 'in_progress', 'review', 'completed'];
        $labels = ['To Do', 'In Progress', 'Review', 'Done'];
        $values = [];
        foreach ($statuses as $i => $code) {
            $count = $db->table('tasks t')
                ->join('task_status ts', 'ts.task_id = t.id AND ts.is_active = 1 AND ts.is_delete = 0', 'left')
                ->join('status_lookup sl', 'sl.id = ts.status_id AND sl.code = "' . $code . '" AND sl.is_delete = 0', 'left')
                ->where('t.project_id', $projectId)
                ->where('t.is_delete', 0)
                ->where('sl.id IS NOT NULL')
                ->countAllResults();
            $values[] = $count;
        }
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    // Get all task templates
    public function getTaskTemplates()
    {
        $builder = $this->db->table('task_templates');
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        return $builder->get()->getResultArray();
    }

    // Get template by code
    public function getTaskTemplateByCode($code)
    {
        $builder = $this->db->table('task_templates');
        $builder->where('code', $code);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        return $builder->get()->getRowArray();
    }

    // Get tasks by template code
    public function getTasksByTemplate($template_code)
    {
        $template = $this->getTaskTemplateByCode($template_code);
        if (!$template) return [];
        $template_id = $template['id'];
        $builder = $this->db->table('tasks');
        $builder->where('template_id', $template_id);
        $builder->where('is_delete', 0);
        $tasks = $builder->get()->getResultArray();
        // Decode data JSON for each task
        foreach ($tasks as &$task) {
            $data = isset($task['data']) ? json_decode($task['data'], true) : [];
            if (is_array($data)) {
                $task = array_merge($task, $data);
            }
        }
        unset($task);
        return $tasks;
    }

    // Get progress for a template (dummy: % completed tasks)
    public function getTemplateProgress($template_code)
    {
        $template = $this->getTaskTemplateByCode($template_code);
        if (!$template) return 0;
        $total = $this->db->table('tasks')->where('template_id', $template['id'])->where('is_delete', 0)->countAllResults();
        if ($total == 0) return 0;
        $completed = $this->db->table('tasks')->where('template_id', $template['id'])->where('is_active', 1)->where('is_delete', 0)->countAllResults();
        return round(($completed / $total) * 100, 2);
    }

    // Autosave task
    public function autosaveTask($taskId, $data)
    {
        if (!$taskId) return false;
        $update = [];
        if (isset($data['data'])) {
            $update['data'] = $data['data'];
        }
        $update['date_modified'] = date('Y-m-d H:i:s');
        return $this->db->table('tasks')->where('id', $taskId)->update($update);
    }

        // Delete a task by ID (soft delete)
    public function deleteTaskById($taskId)
    {
        if (!$taskId) return false;
        // Soft delete: set is_delete=1 if column exists, else hard delete
        if ($this->db->getFieldData('tasks', 'is_delete')) {
            return $this->db->table('tasks')->where('id', $taskId)->update(['is_delete' => 1]);
        } else {
            return $this->db->table('tasks')->where('id', $taskId)->delete();
        }
    }

        // Insert a new dynamic task (for Excel-like table)
    public function insertDynamicTask($data)
    {
        if (empty($data['project_id']) || empty($data['template_id']) || empty($data['data'])) return false;
        $this->db->table('tasks')->insert([
            'project_id' => $data['project_id'],
            'template_id' => $data['template_id'],
            'data' => $data['data'],
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0
        ]);
        return $this->db->insertID();
    }

    // Get tasks by template code and project id
    public function getTasksByTemplateAndProject($template_code, $project_id)
    {
        $template = $this->getTaskTemplateByCode($template_code);
        if (!$template) return [];
        $template_id = $template['id'];
        $builder = $this->db->table('tasks');
        $builder->where('template_id', $template_id);
        $builder->where('project_id', $project_id);
        $builder->where('is_delete', 0);
        $tasks = $builder->get()->getResultArray();
        // Decode data JSON for each task
        foreach ($tasks as &$task) {
            $data = isset($task['data']) ? json_decode($task['data'], true) : [];
            if (is_array($data)) {
                $task = array_merge($task, $data);
            }
        }
        unset($task);
        return $tasks;
    }
}
