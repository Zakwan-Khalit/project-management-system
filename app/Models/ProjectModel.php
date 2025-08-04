<?php
namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get status options for projects
     */
    public function getStatusOptions()
    {
        return [
            ['code' => 'pending', 'name' => 'Pending'],
            ['code' => 'in_progress', 'name' => 'In Progress'],
            ['code' => 'completed', 'name' => 'Completed'],
            ['code' => 'on_hold', 'name' => 'On Hold'],
            ['code' => 'cancelled', 'name' => 'Cancelled']
        ];
    }

    /**
     * Get status colors for projects
     */
    public function getStatusColors()
    {
        return [
            'pending' => 'secondary',
            'in_progress' => 'primary',
            'completed' => 'success',
            'on_hold' => 'warning',
            'cancelled' => 'danger'
        ];
    }

    /**
     * Get all departments from department_lookup
     */
    public function getDepartments()
    {
        return $this->db->table('department_lookup')
            ->select('id, code, name')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get all users in a department (by department_lookup.id), excluding users already in the project
     * Joins user_rel, users, user_profile
     * @param int $departmentId
     * @param int|null $projectId
     * @return array
     */
    public function getUsersByDepartment($departmentId, $projectId = null)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id, up.full_name, u.email');
        $builder->join('user_rel ur', 'ur.user_id = u.id AND ur.is_active = 1 AND ur.is_delete = 0', 'inner');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->where('u.is_active', 1);
        $builder->where('u.is_delete', 0);
        $builder->where('ur.department_id', $departmentId);
        if ($projectId !== null) {
            $builder->where('u.id NOT IN (SELECT user_id FROM project_members WHERE project_id = ' . (int)$projectId . ' AND is_active = 1 AND is_delete = 0)');
        }
        $builder->groupBy('u.id');
        $builder->orderBy('up.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get all positions from position_lookup
     */
    public function getPositions()
    {
        return $this->db->table('position_lookup')
            ->select('id, code, name')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get all users in a position (by position_lookup.id), excluding users already in the project
     * Joins user_rel, users, user_profile
     * @param int $positionId
     * @param int|null $projectId
     * @return array
     */
    public function getUsersByPosition($positionId, $projectId = null)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id, up.full_name, u.email');
        $builder->join('user_rel ur', 'ur.user_id = u.id AND ur.is_active = 1 AND ur.is_delete = 0', 'inner');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->where('u.is_active', 1);
        $builder->where('u.is_delete', 0);
        $builder->where('ur.position_id', $positionId);
        if ($projectId !== null) {
            $builder->where('u.id NOT IN (SELECT user_id FROM project_members WHERE project_id = ' . (int)$projectId . ' AND is_active = 1 AND is_delete = 0)');
        }
        $builder->groupBy('u.id');
        $builder->orderBy('up.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }
    /**
     * Create a new project and return its ID
     * @param array $data
     * @return int|false New project ID or false on failure
     */
    public function createProject($data)
    {
        $insert = [
            'name'         => $data['name'] ?? null,
            'code'         => $data['code'] ?? null,
            'description'  => $data['description'] ?? null,
            'client'       => $data['client'] ?? null,
            'start_date'   => $data['start_date'] ?? null,
            'end_date'     => $data['end_date'] ?? null,
            'budget'       => $data['budget'] ?? null,
            'is_active'    => $data['is_active'] ?? 1,
            'is_delete'    => $data['is_delete'] ?? 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified'=> date('Y-m-d H:i:s'),
        ];
        // pr($insert);
        $result = $this->db->table('projects')->insert($insert);
        if ($result) {
            return $this->db->insertID();
        }
        return false;
    }

    /**
     * Update an existing project
     * @param int $projectId
     * @param array $data
     * @return bool Success status
     */
    public function updateProject($projectId, $data)
    {
        $update = [
            'date_modified' => date('Y-m-d H:i:s')
        ];
        
        // Only update fields that are provided
        if (isset($data['name'])) $update['name'] = $data['name'];
        if (isset($data['code'])) $update['code'] = $data['code'];
        if (isset($data['description'])) $update['description'] = $data['description'];
        if (isset($data['start_date'])) $update['start_date'] = $data['start_date'];
        if (isset($data['end_date'])) $update['end_date'] = $data['end_date'];
        if (isset($data['budget'])) $update['budget'] = $data['budget'];
        if (isset($data['client'])) $update['client'] = $data['client'];
        
        return $this->db->table('projects')
            ->where('id', $projectId)
            ->where('is_delete', 0)
            ->update($update);
    }

    public function getProjectStatusByCode($code)
    {
        return $this->db->table('status_lookup')
            ->where('type', 'project')
            ->where('code', $code)
            ->where('is_delete', 0)
            ->get()->getRowArray();
    }

    /**
     * Set the current status for a project (deactivate old, insert new)
     */
    public function setProjectStatusById($projectId, $statusId, $changedBy = null, $notes = null)
    {
        // Mark current as not current
        $this->db->table('project_status')
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->update(['is_active' => 0, 'date_modified' => date('Y-m-d H:i:s')]);

        // Insert new status
        return $this->db->table('project_status')->insert([
            'project_id' => $projectId,
            'status_id' => $statusId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ]);
    }
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
        // (duplicate code removed)
    }

    public function updateTaskOrder($taskOrder)
    {
        if (!is_array($taskOrder)) return false;
        foreach ($taskOrder as $idx => $taskId) {
            $this->db->table('tasks')->where('id', $taskId)->update(['`task_order`' => $idx + 1]);
        }
        return true;
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
        // Always fetch the project base data first
        $project = $this->db->table('projects')
            ->where('id', $projectId)
            ->where('is_delete', 0)
            ->get()->getRowArray();
        if (!$project) return null;

        // Fetch status (if any)
        $status = $this->db->table('project_status ps')
            ->select('sl.name as status_name, sl.color as status_color')
            ->join('status_lookup sl', 'sl.id = ps.status_id AND sl.is_delete = 0', 'left')
            ->where('ps.project_id', $projectId)
            ->where('ps.is_active', 1)
            ->where('ps.is_delete', 0)
            ->get()->getRowArray();
        if ($status) {
            $project['status_name'] = $status['status_name'];
            $project['status_color'] = $status['status_color'];
        } else {
            $project['status_name'] = null;
            $project['status_color'] = null;
        }

        // Fetch priority (if any)
        $priority = $this->db->table('project_priority pp')
            ->select('pl.name as priority_name, pl.color as priority_color, pl.level as priority_level')
            ->join('priority_lookup pl', 'pl.id = pp.priority_id AND pl.is_delete = 0', 'left')
            ->where('pp.project_id', $projectId)
            ->where('pp.is_active', 1)
            ->where('pp.is_delete', 0)
            ->get()->getRowArray();
        if ($priority) {
            $project['priority_name'] = $priority['priority_name'];
            $project['priority_color'] = $priority['priority_color'];
            $project['priority_level'] = $priority['priority_level'];
        } else {
            $project['priority_name'] = null;
            $project['priority_color'] = null;
            $project['priority_level'] = null;
        }

        return $project;
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

    // Get active projects for dropdown selections
    public function getActiveProjects()
    {
        $builder = $this->db->table('projects');
        $builder->select('id, name, code');
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('name', 'ASC');

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
        $builder->groupBy('p.id');
        $builder->orderBy('p.date_created', 'DESC');

        $projects = $builder->get()->getResultArray();
        // Ensure every project has a team_members array and calculate progress
        foreach ($projects as &$project) {
            $project['team_members'] = $this->getProjectMembers($project['id']) ?? [];
            
            // Calculate average progress from task data JSON
            $taskBuilder = $this->db->table('tasks');
            $taskBuilder->select('data');
            $taskBuilder->where('project_id', $project['id']);
            $taskBuilder->where('is_delete', 0);
            $tasks = $taskBuilder->get()->getResultArray();
            
            $progressSum = 0;
            $progressCount = 0;
            
            foreach ($tasks as $task) {
                $data = json_decode($task['data'], true);
                if (is_array($data)) {
                    // Look for any field containing a percentage (%)
                    foreach ($data as $value) {
                        if (is_string($value) && strpos($value, '%') !== false) {
                            $progress = trim($value);
                            $progress = rtrim($progress, '%');
                            if (is_numeric($progress)) {
                                $progressSum += floatval($progress);
                                $progressCount++;
                                break; // Only count one progress value per task
                            }
                        }
                    }
                }
            }
            
            // Set average progress
            $project['avg_progress'] = $progressCount > 0 ? ($progressSum / $progressCount) : 0;
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
            ->update(['is_active' => 0, 'date_modified' => date('Y-m-d H:i:s')]);

        // Insert new status
        return $this->db->table('project_status')->insert([
            'project_id' => $projectId,
            'status_id' => $statusId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ]);
    }

    // Set current priority for a project
    public function setProjectPriority($projectId, $priorityId, $changedBy = null, $notes = null)
    {
        $this->db->table('project_priority')
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->update(['is_active' => 0, 'date_modified' => date('Y-m-d H:i:s')]);

        return $this->db->table('project_priority')->insert([
            'project_id' => $projectId,
            'priority_id' => $priorityId,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
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

    // Remove a member from a project (soft delete)
    public function removeProjectMember($projectId, $userId)
    {
        return $this->db->table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->update([
                'is_active' => 0, 
                'is_delete' => 1,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
    }

    // Get all members of a project
    public function getProjectMembers($projectId)
    {
        $builder = $this->db->table('project_members pm');
        $builder->select('
            pm.user_id,
            u.email,
            up.full_name,
            pl.name as role
        ');
        $builder->join('users u', 'u.id = pm.user_id AND u.is_delete = 0');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->join('user_rel ur', 'ur.user_id = u.id AND ur.is_active = 1 AND ur.is_delete = 0', 'left');
        $builder->join('position_lookup pl', 'pl.id = ur.position_id AND pl.is_delete = 0', 'left');
        $builder->where('pm.project_id', $projectId);
        $builder->where('pm.is_active', 1);
        $builder->where('pm.is_delete', 0);
        $builder->groupBy('pm.user_id'); // Add GROUP BY to prevent duplicates
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
            return $this->db->table('tasks')->where('id', $taskId)->update(['is_delete' => 1, 'is_active' => 0]);
        } else {
            return $this->db->table('tasks')->where('id', $taskId)->delete();
        }
    }

        // Insert a new dynamic task (for Excel-like table)
    public function insertDynamicTask($data)
    {
        if (empty($data['project_id']) || empty($data['template_id']) || empty($data['data'])) return false;
        // Find max task_order for this project/template
        $builder = $this->db->table('tasks');
        $builder->selectMax('`task_order`', 'max_order');
        $builder->where('project_id', $data['project_id']);
        $builder->where('template_id', $data['template_id']);
        $maxOrderRow = $builder->get()->getRowArray();
        $nextOrder = isset($maxOrderRow['max_order']) ? ((int)$maxOrderRow['max_order'] + 1) : 1;
        $this->db->table('tasks')->insert([
            'project_id' => $data['project_id'],
            'template_id' => $data['template_id'],
            'data' => $data['data'],
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
            'is_active' => 1,
            'is_delete' => 0,
            '`task_order`' => $nextOrder
        ]);
        return $this->db->insertID();
    }

    /**
     * Get all active users for a project (for dropdowns like Tester Name, PIC)
     * Returns: array of [user_id, email, full_name]
     */
    public function getProjectUsers($projectId)
    {
        $builder = $this->db->table('project_members pm');
        $builder->select('u.id as user_id, u.email, up.full_name');
        $builder->join('users u', 'u.id = pm.user_id AND u.is_delete = 0');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->where('pm.project_id', $projectId);
        $builder->where('pm.is_active', 1);
        $builder->where('pm.is_delete', 0);
        $builder->orderBy('up.full_name', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Create a new template for a project
    public function createTaskTemplate($name, $projectId)
    {
        $data = [
            'name' => $name,
            'project_id' => $projectId,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
            'fields' => json_encode([])
        ];
        $this->db->table('task_templates')->insert($data);
        return $this->db->insertID();
    }

    public function getTaskHeadersByIds($ids)
    {
        if (!is_array($ids) || empty($ids)) return [];
        $builder = $this->db->table('task_headers');
        $builder->whereIn('id', $ids);
        $builder->where('is_active', 1);
        $builder->where('is_delete', 0);
        $headers = $builder->get()->getResultArray();
        // Sort headers by $ids order
        $idMap = array_flip($ids);
        usort($headers, function($a, $b) use ($idMap) {
            return $idMap[$a['id']] <=> $idMap[$b['id']];
        });
        return $headers;
    }

    /**
     * Get all header_lookup options for dropdown (can have duplicates)
     */
    public function getHeaderLookupOptions()
    {
        return $this->db->table('header_lookup')->get()->getResultArray();
    }

    /**
     * Insert a new header: always add to header_lookup, then to task_headers
     * Returns the new task_headers.id
     */
    public function insertTaskHeader($columnName)
    {
        $lookupRow = $this->db->table('header_lookup')->where('column_name', $columnName)->get()->getRowArray();
        if (!$lookupRow) {
            $this->db->table('header_lookup')->insert(['column_name' => $columnName]);
        }
        // Insert into task_headers (for template logic) if not exists
        $headerRow = $this->db->table('task_headers')->where('column_name', $columnName)->where('is_delete', 0)->get()->getRowArray();
        if (!$headerRow) {
            $this->db->table('task_headers')->insert([
                'column_name' => $columnName,
                'is_active' => 1,
                'is_delete' => 0,
                'date_created' => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s')
            ]);
            return $this->db->insertID(); // Return new task_headers.id
        } else {
            return $headerRow['id'];
        }
    }

    /**
     * Update the fields (headers) for a task template
     * @param int $templateId
     * @param array $fields (array of header names)
     * @return bool
     */
    public function updateTaskTemplateFields($templateId, $fields)
    {
        // $fields is now an array of header IDs (integers)
        if (!$templateId || !is_array($fields)) return false;
        $headerIds = [];
        foreach ($fields as $headerId) {
            // Only use valid header IDs that exist in task_headers
            $row = $this->db->table('task_headers')->where('id', $headerId)->where('is_delete', 0)->get()->getRowArray();
            if ($row) {
                $headerIds[] = $headerId;
            }
        }
        $fieldsJson = json_encode(array_values($headerIds));
        return $this->db->table('task_templates')->where('id', $templateId)->update(['fields' => $fieldsJson]);
    }

    /**
     * Get task headers by array of IDs, sorted as per IDs order
     * @param int $templateId
     * @return array
     */
    public function getTaskHeadersByTemplateId($templateId)
    {
        $builder = $this->db->table('task_templates');
        $template = $builder->where('id', $templateId)->get()->getRowArray();
        if (!$template || empty($template['fields'])) return [];
        $ids = json_decode($template['fields'], true);
        if (!is_array($ids)) return [];
        $headerBuilder = $this->db->table('task_headers');
        $headers = [];
        foreach ($ids as $id) {
            $row = $headerBuilder->where('id', $id)->get()->getRowArray();
            if ($row) $headers[] = $row;
        }
        return $headers;
    }

    public function addProjectMembersBulk($projectId, $userIds, $referenceId = null, $role = 'member', $assignedBy = null, $referenceType = 'department')
    {
        $added = [];
        $failed = [];
        if (!is_array($userIds) || empty($userIds)) return ['success' => false, 'added' => [], 'failed' => []];
        foreach ($userIds as $userId) {
            // Optionally, check department/position match here if needed
            $result = $this->addProjectMember($projectId, $userId, $role, $assignedBy);
            if ($result) {
                $added[] = $userId;
            } else {
                $failed[] = $userId;
            }
        }
        return ['success' => count($added) > 0, 'added' => $added, 'failed' => $failed];
    }
}
