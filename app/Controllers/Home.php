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
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        try {
            // Get dashboard stats
            $stats = $this->getDashboardStats($userId);
            
            // Get recent projects
            $projects = $this->projectModel->getUserProjects($userId, 5);
            
            // Get user's tasks
            $myTasks = $this->taskModel->getUserTasks($userId, 5);
            
            // Get recent activities
            $recentActivities = $this->activityLog->getRecentActivity(10);
            
            $data = [
                'stats' => $stats,
                'projects' => $projects ?? [],
                'myTasks' => $myTasks ?? [],
                'recentActivities' => $recentActivities ?? [],
                'teamCount' => $this->userModel->countAll()
            ];
            
            return $this->template->member('dashboard', $data);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            
            // Return dashboard with empty data on error
            $data = [
                'stats' => [],
                'projects' => [],
                'myTasks' => [],
                'recentActivities' => []
            ];
            
            return $this->template->member('dashboard', $data);
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
        
        try {
            // Use model methods instead of direct database queries
            $projectStats = $this->projectModel->getDashboardProjectStats($userId);
            $taskStats = $this->taskModel->getDashboardTaskStats($userId);
            
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
        try {
            // Use model method instead of direct database query
            return $this->taskModel->getTaskStatusChartData($userId);
        } catch (\Exception $e) {
            log_message('error', 'getTaskStatusChart error: ' . $e->getMessage());
            return $this->response->setJSON([
                'labels' => [],
                'data' => [],
                'colors' => []
            ]);
        }
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
        try {
            // Use model method instead of direct database query
            return $this->taskModel->getTaskPriorityChartData($userId);
        } catch (\Exception $e) {
            log_message('error', 'getTaskPriorityChart error: ' . $e->getMessage());
            return $this->response->setJSON([
                'labels' => [],
                'data' => [],
                'colors' => []
            ]);
        }
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
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }
        
        try {
            // Get recent activities - use getUserActivity for user-specific activities
            $activities = $this->activityLog->getUserActivity($userId, 10);
            
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
