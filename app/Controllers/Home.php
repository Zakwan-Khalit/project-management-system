<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use App\Models\ReportsModel;

class Home extends BaseController
{
    protected $projectModel;
    protected $taskModel;
    protected $userModel;
    protected $activityLog;
    protected $reportsModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
        $this->reportsModel = new ReportsModel();
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
        
        $statusData = $this->reportsModel->getProjectCompletionStatusData($userId);
        if (!$userId) {
            log_message('warning', 'Dashboard access attempted without valid user session');
            return redirect()->to(base_url('login'));
        }
        
        try {
            // Get dashboard stats with error handling
            $stats = $this->getDashboardStats($userId);
            
            // Get recent projects with error handling
            $projects = [];
            try {
                $projects = $this->projectModel->getUserProjects($userId) ?? [];
            } catch (\Exception $e) {
                log_message('error', 'Failed to get user projects: ' . $e->getMessage());
                $projects = [];
            }
            
            // Get recent activities with error handling
            $recentActivities = [];
            try {
                $recentActivities = $this->activityLog->getRecentActivity(10) ?? [];
            } catch (\Exception $e) {
                log_message('error', 'Failed to get recent activities: ' . $e->getMessage());
                $recentActivities = [];
            }
            
            // Get team count with error handling
            $teamCount = 0;
            try {
                $teamCount = $this->userModel->countAll() ?? 0;
            } catch (\Exception $e) {
                log_message('error', 'Failed to get team count: ' . $e->getMessage());
                $teamCount = 0;
            }
            
            $data = [
                'stats' => $stats,
                'projects' => $projects,
                'recentActivities' => $recentActivities,
                'teamCount' => $teamCount,
                'title' => 'Dashboard - keratsk',
                'statusData' => $statusData
            ];
            
            return $this->template->member('dashboard', $data);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard critical error: ' . $e->getMessage());
            
            // Return dashboard with empty data on critical error
            $data = [
                'stats' => [
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
                ],
                'projects' => [],
                'recentActivities' => [],
                'teamCount' => 0,
                'title' => 'Dashboard - keratsk',
                'error_message' => 'Unable to load dashboard data. Please try again later.'
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
            // Use model methods for base stats
            $projectStats = $this->projectModel->getDashboardProjectStats($userId);
            $taskStats = $this->taskModel->getDashboardTaskStats($userId);

            // --- Additional logic to check project_status and status_lookup ---
            $db = \Config\Database::connect();
            // Get status_lookup for 'Completed' and 'Delayed'
            $statusRows = $db->table('status_lookup')->whereIn('name', ['Completed', 'Delayed'])->get()->getResultArray();
            $statusMap = [];
            foreach ($statusRows as $row) {
                $statusMap[$row['name']] = $row['id'];
            }

            // Get all user's projects
            $projects = $db->table('projects')->where('user_id', $userId)->get()->getResultArray();
            $completedCount = 0;
            $delayedCount = 0;
            foreach ($projects as $project) {
                // Get latest status for this project
                $statusRow = $db->table('project_status')
                    ->where('project_id', $project['id'])
                    ->orderBy('date_modified', 'DESC')
                    ->get(1)->getRowArray();
                if ($statusRow) {
                    if (isset($statusMap['Completed']) && $statusRow['status_id'] == $statusMap['Completed']) {
                        $completedCount++;
                    } elseif (isset($statusMap['Delayed']) && $statusRow['status_id'] == $statusMap['Delayed']) {
                        $delayedCount++;
                    }
                }
            }

            // Overwrite completed_projects and delayed_projects if needed
            if (is_array($projectStats)) {
                $projectStats['completed_projects'] = $completedCount;
                $projectStats['delayed_projects'] = $delayedCount;
            }

            return [
                'projects' => $projectStats ?: [
                    'total_projects' => 0,
                    'active_projects' => 0,
                    'completed_projects' => 0,
                    'on_hold_projects' => 0,
                    'delayed_projects' => 0
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
                    'on_hold_projects' => 0,
                    'delayed_projects' => 0
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
