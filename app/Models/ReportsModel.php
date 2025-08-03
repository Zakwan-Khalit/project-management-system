<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get project statistics
     */
    public function getProjectStats()
    {
        // Total projects
        $totalProjects = $this->db->table('projects')
            ->where('is_delete', 0)
            ->countAllResults();

        // Projects by status
        $projectsByStatus = $this->db->table('projects p')
            ->select('sl.name as status_name, sl.code as status_code, COUNT(ps.project_id) as count')
            ->join('project_status ps', 'p.id = ps.project_id AND ps.is_active = 1 AND ps.is_delete = 0', 'left')
            ->join('status_lookup sl', 'ps.status_id = sl.id AND sl.type = "project"', 'left')
            ->where('p.is_delete', 0)
            ->groupBy('sl.id, sl.name, sl.code')
            ->get()
            ->getResultArray();

        // Count active, completed, and other projects
        $activeProjects = 0;
        $completedProjects = 0;
        $statusDistribution = [];

        foreach ($projectsByStatus as $status) {
            $statusDistribution[$status['status_name'] ?? 'No Status'] = (int)$status['count'];
            
            if ($status['status_code'] === 'active') {
                $activeProjects = (int)$status['count'];
            } elseif ($status['status_code'] === 'completed') {
                $completedProjects = (int)$status['count'];
            }
        }

        return [
            'total' => $totalProjects,
            'active' => $activeProjects,
            'completed' => $completedProjects,
            'distribution' => $statusDistribution
        ];
    }

    /**
     * Get task statistics
     */
    public function getTaskStats()
    {
        // Total tasks
        $totalTasks = $this->db->table('tasks')
            ->where('is_delete', 0)
            ->countAllResults();

        // Tasks by status
        $tasksByStatus = $this->db->table('tasks t')
            ->select('sl.name as status_name, sl.code as status_code, COUNT(ts.task_id) as count')
            ->join('task_status ts', 't.id = ts.task_id AND ts.is_active = 1 AND ts.is_delete = 0', 'left')
            ->join('status_lookup sl', 'ts.status_id = sl.id AND sl.type = "task"', 'left')
            ->where('t.is_delete', 0)
            ->groupBy('sl.id, sl.name, sl.code')
            ->get()
            ->getResultArray();

        // Count completed tasks and build distribution
        $completedTasks = 0;
        $statusDistribution = [];

        foreach ($tasksByStatus as $status) {
            $statusDistribution[$status['status_name'] ?? 'No Status'] = (int)$status['count'];
            
            if ($status['status_code'] === 'completed') {
                $completedTasks = (int)$status['count'];
            }
        }

        return [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'distribution' => $statusDistribution
        ];
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        // Total active users
        $totalUsers = $this->db->table('users')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->countAllResults();

        return [
            'total' => $totalUsers
        ];
    }

    /**
     * Get project completion rates
     */
    public function getProjectCompletionRates()
    {
        return $this->db->table('projects p')
            ->select('p.id, p.name, p.code, 
                     COUNT(t.id) as total_tasks,
                     COUNT(CASE WHEN ts.status_id = (SELECT id FROM status_lookup WHERE type="task" AND code="completed" LIMIT 1) THEN 1 END) as completed_tasks,
                     ROUND((COUNT(CASE WHEN ts.status_id = (SELECT id FROM status_lookup WHERE type="task" AND code="completed" LIMIT 1) THEN 1 END) / COUNT(t.id)) * 100, 2) as completion_rate')
            ->join('tasks t', 'p.id = t.project_id AND t.is_delete = 0', 'left')
            ->join('task_status ts', 't.id = ts.task_id AND ts.is_active = 1 AND ts.is_delete = 0', 'left')
            ->where('p.is_delete', 0)
            ->groupBy('p.id, p.name, p.code')
            ->having('total_tasks >', 0)
            ->orderBy('completion_rate', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get team productivity metrics
     */
    public function getTeamProductivity()
    {
        return $this->db->table('users u')
            ->select('u.id, u.email, up.full_name,
                     COUNT(pm.project_id) as assigned_projects,
                     COUNT(ta.task_id) as assigned_tasks,
                     COUNT(CASE WHEN ts.status_id = (SELECT id FROM status_lookup WHERE type="task" AND code="completed" LIMIT 1) THEN 1 END) as completed_tasks')
            ->join('user_profile up', 'u.id = up.user_id', 'left')
            ->join('project_members pm', 'u.id = pm.user_id AND pm.is_delete = 0', 'left')
            ->join('task_assignment ta', 'u.id = ta.user_id AND ta.is_delete = 0', 'left')
            ->join('task_status ts', 'ta.task_id = ts.task_id AND ts.is_active = 1 AND ts.is_delete = 0', 'left')
            ->where('u.is_active', 1)
            ->where('u.is_delete', 0)
            ->groupBy('u.id, u.email, up.full_name')
            ->orderBy('completed_tasks', 'DESC')
            ->get()
            ->getResultArray();
    }
}
