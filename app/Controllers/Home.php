<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Home extends BaseController
{
    protected $projectModel;
    protected $taskModel;
    protected $userModel;
    protected $activityLog;
    
    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
    }
    
    public function index()
    {
        // Check if user is logged in
        if (!session('is_logged_in') || !session('userdata')) {
            return redirect()->to(base_url('login'));
        }
        
        
        return $this->dashboard();
    }
    
    public function dashboard()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        // Ensure user is logged in and has valid user ID
        if (!$userId || !is_numeric($userId)) {
            return redirect()->to(base_url('login'));
        }
        
        // Ensure user ID is integer
        $userId = (int)$userId;
        
        try {
            // Get dashboard data with error handling
            $projects = $this->projectModel->getUserProjects($userId);
            $myTasks = $this->taskModel->getUserTasks($userId);
            $overdueTasks = $this->taskModel->getOverdueTasks();
            $recentActivities = $this->activityLog->getRecentActivities(10);
            
            // Get statistics
            $stats = $this->getDashboardStats($userId);
            
            $data = [
                'title' => 'Dashboard',
                'user' => $userData, // Pass the entire userdata array
                'projects' => $projects,
                'myTasks' => $myTasks,
                'overdueTasks' => $overdueTasks,
                'recentActivities' => $recentActivities,
                'stats' => $stats
            ];
            
            $this->template->member('dashboard', $data);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            // Redirect to login if there's a database error (might be session issue)
            return redirect()->to(base_url('login'));
        }
    }
    
    private function getDashboardStats($userId)
    {
        // Ensure userId is valid
        if (!$userId || !is_numeric($userId)) {
            return [
                'projects' => [
                    'total_projects' => 0,
                    'active_projects' => 0,
                    'completed_projects' => 0,
                    'on_hold_projects' => 0
                ],
                'tasks' => [
                    'total_tasks' => 0,
                    'completed_tasks' => 0,
                    'in_progress_tasks' => 0,
                    'pending_tasks' => 0,
                    'overdue_tasks' => 0
                ]
            ];
        }
        
        $userId = (int)$userId;
        $db = \Config\Database::connect();
        
        try {
            // Project stats - projects table doesn't have is_deleted field
            $projectStats = $db->query("
                SELECT 
                    COUNT(*) as total_projects,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_projects,
                    COUNT(CASE WHEN status = 'on_hold' THEN 1 END) as on_hold_projects
                FROM projects p
                JOIN project_members pm ON pm.project_id = p.id
                WHERE pm.user_id = ?
            ", [$userId])->getRowArray();
            
            // Task stats
            $taskStats = $db->query("
                SELECT 
                    COUNT(*) as total_tasks,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_tasks,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress_tasks,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_tasks,
                    COUNT(CASE WHEN due_date < CURDATE() AND status != 'completed' THEN 1 END) as overdue_tasks
                FROM tasks 
                WHERE assigned_to = ? AND is_delete = 0
            ", [$userId])->getRowArray();
            
            return [
                'projects' => $projectStats ?: [
                    'total_projects' => 0,
                    'active_projects' => 0,
                    'completed_projects' => 0,
                    'on_hold_projects' => 0
                ],
                'tasks' => $taskStats ?: [
                    'total_tasks' => 0,
                    'completed_tasks' => 0,
                    'in_progress_tasks' => 0,
                    'pending_tasks' => 0,
                    'overdue_tasks' => 0
                ]
            ];
        } catch (\Exception $e) {
            log_message('error', 'getDashboardStats error: ' . $e->getMessage());
            return [
                'projects' => [
                    'total_projects' => 0,
                    'active_projects' => 0,
                    'completed_projects' => 0,
                    'on_hold_projects' => 0
                ],
                'tasks' => [
                    'total_tasks' => 0,
                    'completed_tasks' => 0,
                    'in_progress_tasks' => 0,
                    'pending_tasks' => 0,
                    'overdue_tasks' => 0
                ]
            ];
        }
    }
    
    public function getChartData()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }
        
        $type = $this->request->getGet('type');
        
        switch ($type) {
            case 'task_status':
                return $this->getTaskStatusChart($userId);
            case 'project_progress':
                return $this->getProjectProgressChart($userId);
            case 'task_priority':
                return $this->getTaskPriorityChart($userId);
            default:
                return $this->response->setJSON(['error' => 'Invalid chart type']);
        }
    }
    
    private function getTaskStatusChart($userId)
    {
        $db = \Config\Database::connect();
        
        $data = $db->query("
            SELECT 
                status,
                COUNT(*) as count
            FROM tasks 
            WHERE assigned_to = ? AND is_delete = 0
            GROUP BY status
        ", [$userId])->getResultArray();
        
        $labels = [];
        $values = [];
        $colors = [
            'pending' => '#ffc107',
            'in_progress' => '#007bff',
            'review' => '#fd7e14',
            'completed' => '#28a745'
        ];
        
        foreach ($data as $row) {
            $labels[] = ucfirst(str_replace('_', ' ', $row['status']));
            $values[] = $row['count'];
        }
        
        return $this->response->setJSON([
            'labels' => $labels,
            'data' => $values,
            'colors' => array_values($colors)
        ]);
    }
    
    private function getProjectProgressChart($userId)
    {
        $projects = $this->projectModel->getUserProjects($userId);
        
        $labels = [];
        $values = [];
        
        foreach ($projects as $project) {
            $labels[] = $project['name'];
            $values[] = $project['progress'];
        }
        
        return $this->response->setJSON([
            'labels' => $labels,
            'data' => $values
        ]);
    }
    
    private function getTaskPriorityChart($userId)
    {
        $db = \Config\Database::connect();
        
        $data = $db->query("
            SELECT 
                priority,
                COUNT(*) as count
            FROM tasks 
            WHERE assigned_to = ? AND is_delete = 0
            GROUP BY priority
        ", [$userId])->getResultArray();
        
        $labels = [];
        $values = [];
        $colors = [
            'low' => '#28a745',
            'medium' => '#ffc107',
            'high' => '#fd7e14',
            'critical' => '#dc3545'
        ];
        
        foreach ($data as $row) {
            $labels[] = ucfirst($row['priority']);
            $values[] = $row['count'];
        }
        
        return $this->response->setJSON([
            'labels' => $labels,
            'data' => $values,
            'colors' => array_values($colors)
        ]);
    }
    
    public function refresh()
    {
        try {
            // Just return success for now - in real app, could clear cache, etc.
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dashboard refreshed successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to refresh dashboard'
            ]);
        }
    }
    
    public function activityFeed()
    {
        $userId = user_id();
        
        try {
            // Get recent activities - simplified for demo
            $activities = $this->activityLog->getRecentActivities($userId, 10);
            
            return $this->response->setJSON([
                'success' => true,
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load activity feed'
            ]);
        }
    }
    
    public function filter()
    {
        $period = $this->request->getPost('period');
        
        try {
            // Filter logic would go here
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dashboard filtered for ' . $period
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to filter dashboard'
            ]);
        }
    }
    
    // Placeholder for reports page
    public function reports()
    {
        return $this->template->member('dashboard', ['title' => 'Reports', 'content' => '<div class="alert alert-info">Reports page coming soon.</div>']);
    }
}
