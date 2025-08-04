<?php

namespace App\Controllers;

use App\Models\ReportsModel;
use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\ActivityModel;

class Reports extends BaseController
{
    protected $reportsModel;
    protected $projectModel;
    protected $taskModel;
    protected $activityModel;

    public function __construct()
    {
        $this->reportsModel = new ReportsModel();
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->activityModel = new ActivityModel();
    }

    public function index()
    {
        // Check authentication
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Prepare basic data for the view (no projects needed here anymore)
        $data = [
            'title' => 'Reports & Analytics'
        ];

        // Load the view using the template
        return $this->template->member('reports/index', $data);
    }

    public function projectProgress($projectId = null)
    {
        // Check authentication
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        // Get current user ID
        $userId = session()->get('user_id');
        
        // Get projects where the user is a member
        $userProjects = $this->projectModel->getUserProjects($userId);

        // Always show the project selection page with empty table
        $data = [
            'title' => 'Project Progress Report',
            'projects' => $userProjects
        ];

        return $this->template->member('reports/project_progress', $data);
    }

    public function getProjectProgressData()
    {
        // Check authentication
        if (!is_logged_in()) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $projectId = $this->request->getPost('project_id');
        if (!$projectId) {
            return $this->response->setJSON(['error' => 'Project ID is required'])->setStatusCode(400);
        }

        // Get current user ID
        $userId = session()->get('user_id');
        
        // Get projects where the user is a member
        $userProjects = $this->projectModel->getUserProjects($userId);

        // Verify user has access to this project
        $hasAccess = false;
        foreach ($userProjects as $userProject) {
            if ($userProject['id'] == $projectId) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            return $this->response->setJSON(['error' => 'You do not have access to this project'])->setStatusCode(403);
        }

        try {
            // Get project details
            $project = $this->projectModel->getProjectById($projectId);
            if (!$project) {
                return $this->response->setJSON(['error' => 'Project not found'])->setStatusCode(404);
            }

            // Get project progress data
            $progressData = $this->reportsModel->getProjectProgressData($projectId);

            return $this->response->setJSON([
                'success' => true,
                'project' => $project,
                'progressData' => $progressData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Project Progress Data error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to fetch project data'])->setStatusCode(500);
        }
    }

    public function completedProject()
    {
        // Check authentication
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        try {
            // Get completed projects data
            $completedData = $this->reportsModel->getCompletedProjectsData();

            $data = [
                'title' => 'Completed Project Progress Report',
                'completedData' => $completedData,
                'report_type' => 'completed_project'
            ];

            return $this->template->member('reports/completed_project', $data);

        } catch (\Exception $e) {
            log_message('error', 'Completed Project Report error: ' . $e->getMessage());
            return redirect()->to(base_url('reports'))->with('error', 'Failed to generate report');
        }
    }

    public function completionStatus()
    {
        // Check authentication
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        try {
            // Get completion status data
            $statusData = $this->reportsModel->getProjectCompletionStatusData();

            $data = [
                'title' => 'Project Completion Status Report',
                'statusData' => $statusData,
                'report_type' => 'completion_status'
            ];

            return $this->template->member('reports/completion_status', $data);

        } catch (\Exception $e) {
            log_message('error', 'Completion Status Report error: ' . $e->getMessage());
            return redirect()->to(base_url('reports'))->with('error', 'Failed to generate report');
        }
    }

    public function exportCsv($reportType, $projectId = null)
    {
        // Check authentication
        if (!is_logged_in()) {
            return $this->response->setStatusCode(403);
        }

        // Get current user ID for access control
        $userId = session()->get('user_id');

        try {
            $data = [];
            $filename = '';
            $projectName = '';

            switch ($reportType) {
                case 'project_progress':
                    if (!$projectId) {
                        return $this->response->setStatusCode(400);
                    }
                    
                    // Check user access to project
                    $userProjects = $this->projectModel->getUserProjects($userId);
                    $hasAccess = false;
                    foreach ($userProjects as $userProject) {
                        if ($userProject['id'] == $projectId) {
                            $hasAccess = true;
                            $projectName = $userProject['name'];
                            break;
                        }
                    }
                    
                    if (!$hasAccess) {
                        return $this->response->setStatusCode(403);
                    }
                    
                    $data = $this->reportsModel->getProjectProgressData($projectId);
                    $cleanProjectName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $projectName);
                    $filename = $cleanProjectName . '_project_progress_report_' . date('Y-m-d');
                    break;
                
                case 'completed_project':
                    $data = $this->reportsModel->getCompletedProjectsData();
                    $filename = 'completed_projects_report_' . date('Y-m-d');
                    break;
                
                case 'completion_status':
                    $data = $this->reportsModel->getProjectCompletionStatusData();
                    $filename = 'completion_status_report_' . date('Y-m-d');
                    break;
                
                default:
                    return $this->response->setStatusCode(400);
            }

            return $this->generateCsvExport($data, $filename, $reportType, $projectName);

        } catch (\Exception $e) {
            log_message('error', 'CSV Export error: ' . $e->getMessage());
            return $this->response->setStatusCode(500);
        }
    }

    private function generateCsvExport($data, $filename, $reportType, $projectName = '')
    {
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');

        $output = fopen('php://output', 'w');

        // Get report type display name
        $reportTypeNames = [
            'project_progress' => 'Project Progress Report',
            'completed_project' => 'Completed Project Progress Report',
            'completion_status' => 'Project Completion Status Report'
        ];
        
        $reportDisplayName = $reportTypeNames[$reportType] ?? 'Project Report';

        // Add headers based on report type
        if ($reportType === 'project_progress') {
            // Add report type at the top
            fputcsv($output, ['Report Type: ' . $reportDisplayName]);
            // Add project name if provided
            if ($projectName) {
                fputcsv($output, ['Project: ' . $projectName]);
            }
            fputcsv($output, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($output, []); // Empty row for spacing
            
            fputcsv($output, ['NUM', 'Activity', 'Planned Start', 'Planned End', 'Actual Start', 'Actual End', 'Planned %', 'Actual %', 'Variant %', 'Status']);
            foreach ($data as $row) {
                fputcsv($output, [
                    $row['num'] ?? '',
                    $row['activity'],
                    $row['planned_start'],
                    $row['planned_end'],
                    $row['actual_start'],
                    $row['actual_end'],
                    $row['planned_percentage'],
                    $row['actual_percentage'],
                    $row['variant'],
                    $row['status']
                ]);
            }
        } elseif ($reportType === 'completed_project') {
            // Add report type at the top
            fputcsv($output, ['Report Type: ' . $reportDisplayName]);
            fputcsv($output, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($output, []); // Empty row for spacing
            
            fputcsv($output, ['Scope', 'Scope Details', 'Total %', 'Planned %', 'Actual %', 'Variant %']);
            foreach ($data as $row) {
                fputcsv($output, [
                    $row['scope'],
                    $row['scope_details'],
                    $row['total_percentage'],
                    $row['planned_percentage'],
                    $row['actual_percentage'],
                    $row['variant']
                ]);
            }
        } elseif ($reportType === 'completion_status') {
            // Add report type at the top
            fputcsv($output, ['Report Type: ' . $reportDisplayName]);
            fputcsv($output, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($output, []); // Empty row for spacing
            
            fputcsv($output, ['Project Name', 'Status', 'Days Early', 'Days Late']);
            foreach ($data as $row) {
                fputcsv($output, [
                    $row['project_name'],
                    $row['status'],
                    $row['days_early'],
                    $row['days_late']
                ]);
            }
        }

        fclose($output);
        return $this->response;
    }
}