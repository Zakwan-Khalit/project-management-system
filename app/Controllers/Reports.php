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
    protected $template;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->template = new \App\Libraries\Template();
    }

    public function index()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Get overall statistics using model methods (should use builder pattern and lookup tables)
        $projectStats = $this->projectModel->getProjectSummaryStats();
        $taskStats = $this->taskModel->getTaskSummaryStats();
        $totalUsers = $this->userModel->where('is_delete', 0)->countAllResults();
        // Get projects with task completion rates
        $projectsWithStats = $this->projectModel->getProjectsWithTaskStats();
        // Get task status distribution
        $taskStatusData = $this->taskModel->getTaskStatusDistribution();
        // Get project status distribution
        $projectStatusData = $this->projectModel->getProjectStatusDistribution();
        // Get recent activity
        $recentActivity = $this->activityLogModel->getRecentActivityWithUsers(15);
        // Get monthly task completion data for the last 6 months
        $monthlyData = $this->taskModel->getMonthlyCompletionData(6);

        $data = [
            'title' => 'Reports & Analytics',
            'totalProjects' => $projectStats['total'] ?? 0,
            'activeProjects' => $projectStats['active'] ?? 0,
            'completedProjects' => $projectStats['completed'] ?? 0,
            'totalTasks' => $taskStats['total'] ?? 0,
            'completedTasks' => $taskStats['completed'] ?? 0,
            'pendingTasks' => $taskStats['pending'] ?? 0,
            'inProgressTasks' => $taskStats['in_progress'] ?? 0,
            'totalUsers' => $totalUsers,
            'projectStats' => $projectsWithStats,
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
            $tasks = $this->taskModel->getTasksWithDetails($project['id']);
            $totalTasks = count($tasks);
            $completedTasks = count(array_filter($tasks, function($task) {
                return $task['status_code'] === 'completed';
            }));
            $inProgressTasks = count(array_filter($tasks, function($task) {
                return $task['status_code'] === 'in_progress';
            }));
            $pendingTasks = count(array_filter($tasks, function($task) {
                return $task['status_code'] === 'pending';
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
        $highPriorityTasks = $this->taskModel->countTasksByPriority('high');
        $mediumPriorityTasks = $this->taskModel->countTasksByPriority('medium');
        $lowPriorityTasks = $this->taskModel->countTasksByPriority('low');

        // Get overdue tasks
        $overdueTasks = $this->taskModel->getOverdueTasks();

        // Get tasks due this week
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $tasksThisWeek = $this->taskModel->getTasksDueInDateRange($weekStart, $weekEnd);

        // Get task completion trends for the last 30 days
        $dailyCompletions = $this->taskModel->getDailyCompletionsForPeriod(30);

        // Get user productivity (tasks completed by user)
        $userProductivity = $this->taskModel->getUserProductivityStats(10);

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
                $totalTasks = $this->taskModel->where('project_id', $project['id'])->where('is_delete', 0)->countAllResults();
                // For CSV, get project-specific task counts
                $projectTasks = $this->taskModel->getTasksWithDetails($project['id']);
                $completedTasks = count(array_filter($projectTasks, function($task) {
                    return $task['status_code'] === 'completed';
                }));
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
            
            $tasks = $this->taskModel->getTasksWithDetails();
            
            foreach ($tasks as $task) {
                fputcsv($output, [
                    $task['title'],
                    $task['project_name'] ?? 'N/A',
                    $task['status_name'] ?? 'N/A',
                    $task['priority_name'] ?? 'N/A',
                    isset($task['first_name']) && isset($task['last_name']) ? 
                        $task['first_name'] . ' ' . $task['last_name'] : 'Unassigned',
                    $task['due_date'] ?? 'N/A',
                    $task['date_created']
                ]);
            }
        }

        fclose($output);
        exit;
    }
}
