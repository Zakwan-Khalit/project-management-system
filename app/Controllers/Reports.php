<?php

namespace App\Controllers;

use App\Models\ReportsModel;

class Reports extends BaseController
{
    protected $reportsModel;

    public function __construct()
    {
        $this->reportsModel = new ReportsModel();
    }

    public function index()
    {
        // Check authentication
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        try {
            // Get statistics from the reports model
            $projectStats = $this->reportsModel->getProjectStats();
            $taskStats = $this->reportsModel->getTaskStats();
            $userStats = $this->reportsModel->getUserStats();

            // Prepare data for the view
            $data = [
                'title' => 'Reports & Analytics',
                // Project statistics
                'totalProjects' => $projectStats['total'],
                'activeProjects' => $projectStats['active'],
                'completedProjects' => $projectStats['completed'],
                'projectStatusData' => $projectStats['distribution'],
                
                // Task statistics
                'totalTasks' => $taskStats['total'],
                'completedTasks' => $taskStats['completed'],
                'taskStatusData' => $taskStats['distribution'],
                
                // User statistics
                'totalUsers' => $userStats['total']
            ];

            // Load the view using the template
            return $this->template->member('reports/index', $data);

        } catch (\Exception $e) {
            log_message('error', 'Reports controller error: ' . $e->getMessage());
            
            // Return with error message
            $data = [
                'title' => 'Reports & Analytics',
                'error_message' => 'Failed to load reports data: ' . $e->getMessage(),
                'totalProjects' => 0,
                'activeProjects' => 0,
                'completedProjects' => 0,
                'totalTasks' => 0,
                'completedTasks' => 0,
                'totalUsers' => 0,
                'projectStatusData' => [],
                'taskStatusData' => []
            ];

            return $this->template->member('reports/index', $data);
        }
    }
}