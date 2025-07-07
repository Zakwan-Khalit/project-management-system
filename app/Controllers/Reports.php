<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Reports extends BaseController
{
    protected $projectModel;
    protected $taskModel;
    protected $userModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Get overall statistics
        $totalProjects = $this->projectModel->countAll();
        $activeProjects = $this->projectModel->where('status', 'active')->countAllResults();
        $completedProjects = $this->projectModel->where('status', 'completed')->countAllResults();
        
        $totalTasks = $this->taskModel->countAll();
        $completedTasks = $this->taskModel->where('status', 'completed')->countAllResults();
        $pendingTasks = $this->taskModel->where('status', 'pending')->countAllResults();
        $inProgressTasks = $this->taskModel->where('status', 'in_progress')->countAllResults();

        $totalUsers = $this->userModel->countAll();

        // Get projects with task completion rates
        $projects = $this->projectModel->findAll();
        $projectStats = [];
        
        foreach ($projects as $project) {
            $projectTasks = $this->taskModel->where('project_id', $project['id'])->countAllResults();
            $completedProjectTasks = $this->taskModel->where(['project_id' => $project['id'], 'status' => 'completed'])->countAllResults();
            
            $completionRate = $projectTasks > 0 ? round(($completedProjectTasks / $projectTasks) * 100, 2) : 0;
            
            $projectStats[] = [
                'project' => $project,
                'total_tasks' => $projectTasks,
                'completed_tasks' => $completedProjectTasks,
                'completion_rate' => $completionRate
            ];
        }

        // Get task status distribution
        $taskStatusData = [
            'pending' => $pendingTasks,
            'in_progress' => $inProgressTasks,
            'completed' => $completedTasks
        ];

        // Get project status distribution
        $projectStatusData = [
            'active' => $activeProjects,
            'completed' => $completedProjects,
            'on_hold' => $this->projectModel->where('status', 'on_hold')->countAllResults(),
            'cancelled' => $this->projectModel->where('status', 'cancelled')->countAllResults()
        ];

        // Get recent activity
        $recentActivity = $this->activityLogModel->select('activity_logs.*, CONCAT(users.first_name, " ", users.last_name) as user_name, users.email')
            ->join('users', 'users.id = activity_logs.user_id')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->limit(15)
            ->findAll();

        // Get monthly task completion data for the last 6 months
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            $completedInMonth = $this->taskModel->where('status', 'completed')
                ->where("DATE_FORMAT(updated_at, '%Y-%m')", $month)
                ->countAllResults();
            
            $monthlyData[] = [
                'month' => $monthName,
                'completed_tasks' => $completedInMonth
            ];
        }

        $data = [
            'title' => 'Reports & Analytics',
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'totalUsers' => $totalUsers,
            'projectStats' => $projectStats,
            'taskStatusData' => $taskStatusData,
            'projectStatusData' => $projectStatusData,
            'recentActivity' => $recentActivity,
            'monthlyData' => $monthlyData,
            'breadcrumbs' => [
                ['title' => 'Reports']
            ]
        ];

        return $this->template->member('reports/index', $data);
    }

    public function projects()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Get detailed project analytics
        $projects = $this->projectModel->findAll();
        $detailedProjectStats = [];
        
        foreach ($projects as $project) {
            $tasks = $this->taskModel->where('project_id', $project['id'])->findAll();
            $totalTasks = count($tasks);
            $completedTasks = count(array_filter($tasks, function($task) {
                return $task['status'] === 'completed';
            }));
            $inProgressTasks = count(array_filter($tasks, function($task) {
                return $task['status'] === 'in_progress';
            }));
            $pendingTasks = count(array_filter($tasks, function($task) {
                return $task['status'] === 'pending';
            }));

            $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
            
            // Calculate project progress based on dates
            $startDate = new \DateTime($project['start_date']);
            $endDate = new \DateTime($project['end_date']);
            $currentDate = new \DateTime();
            
            $totalDuration = $endDate->diff($startDate)->days;
            $elapsedDuration = $currentDate->diff($startDate)->days;
            $timeProgress = $totalDuration > 0 ? min(100, max(0, round(($elapsedDuration / $totalDuration) * 100, 2))) : 0;

            $detailedProjectStats[] = [
                'project' => $project,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'pending_tasks' => $pendingTasks,
                'completion_rate' => $completionRate,
                'time_progress' => $timeProgress,
                'is_overdue' => $currentDate > $endDate && $project['status'] !== 'completed',
                'days_remaining' => $endDate->diff($currentDate)->days
            ];
        }

        $data = [
            'title' => 'Project Reports',
            'detailedProjectStats' => $detailedProjectStats,
            'breadcrumbs' => [
                ['title' => 'Reports', 'url' => base_url('reports')],
                ['title' => 'Project Reports']
            ]
        ];

        return $this->template->member('reports/projects', $data);
    }

    public function tasks()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Get task analytics by priority
        $highPriorityTasks = $this->taskModel->where('priority', 'high')->countAllResults();
        $mediumPriorityTasks = $this->taskModel->where('priority', 'medium')->countAllResults();
        $lowPriorityTasks = $this->taskModel->where('priority', 'low')->countAllResults();

        // Get overdue tasks
        $overdueTasks = $this->taskModel->where('due_date <', date('Y-m-d'))
            ->where('status !=', 'completed')
            ->findAll();

        // Get tasks due this week
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $tasksThisWeek = $this->taskModel->where('due_date >=', $weekStart)
            ->where('due_date <=', $weekEnd)
            ->findAll();

        // Get task completion trends for the last 30 days
        $dailyCompletions = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $completedCount = $this->taskModel->where('status', 'completed')
                ->where("DATE(updated_at)", $date)
                ->countAllResults();
            
            $dailyCompletions[] = [
                'date' => date('M j', strtotime($date)),
                'completed' => $completedCount
            ];
        }

        // Get user productivity (tasks completed by user)
        $userProductivity = $this->taskModel->select('CONCAT(users.first_name, " ", users.last_name) as user_name, users.email, COUNT(*) as completed_tasks')
            ->join('users', 'users.id = tasks.assigned_to')
            ->where('tasks.status', 'completed')
            ->groupBy('tasks.assigned_to')
            ->orderBy('completed_tasks', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => 'Task Reports',
            'highPriorityTasks' => $highPriorityTasks,
            'mediumPriorityTasks' => $mediumPriorityTasks,
            'lowPriorityTasks' => $lowPriorityTasks,
            'overdueTasks' => $overdueTasks,
            'tasksThisWeek' => $tasksThisWeek,
            'dailyCompletions' => $dailyCompletions,
            'userProductivity' => $userProductivity,
            'breadcrumbs' => [
                ['title' => 'Reports', 'url' => base_url('reports')],
                ['title' => 'Task Reports']
            ]
        ];

        return $this->template->member('reports/tasks', $data);
    }

    public function export($type = 'projects')
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // This is a basic CSV export - you could extend this to support Excel, PDF, etc.
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $type . '_report_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        if ($type === 'projects') {
            fputcsv($output, ['Project Name', 'Status', 'Start Date', 'End Date', 'Progress', 'Total Tasks', 'Completed Tasks']);
            
            $projects = $this->projectModel->findAll();
            foreach ($projects as $project) {
                $totalTasks = $this->taskModel->where('project_id', $project['id'])->countAllResults();
                $completedTasks = $this->taskModel->where(['project_id' => $project['id'], 'status' => 'completed'])->countAllResults();
                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                
                fputcsv($output, [
                    $project['name'],
                    $project['status'],
                    $project['start_date'],
                    $project['end_date'],
                    $progress . '%',
                    $totalTasks,
                    $completedTasks
                ]);
            }
        } else if ($type === 'tasks') {
            fputcsv($output, ['Task Title', 'Project', 'Status', 'Priority', 'Assigned To', 'Due Date', 'Created Date']);
            
            $tasks = $this->taskModel->select('tasks.*, projects.name as project_name, CONCAT(users.first_name, " ", users.last_name) as user_name, users.email')
                ->join('projects', 'projects.id = tasks.project_id')
                ->join('users', 'users.id = tasks.assigned_to', 'left')
                ->findAll();
            
            foreach ($tasks as $task) {
                fputcsv($output, [
                    $task['title'],
                    $task['project_name'],
                    $task['status'],
                    $task['priority'],
                    $task['user_name'] ?? 'Unassigned',
                    $task['due_date'],
                    $task['created_at']
                ]);
            }
        }

        fclose($output);
        exit;
    }
}
