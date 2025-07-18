<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Projects extends BaseController
{
    protected $projectModel;
    protected $taskModel;
    protected $userModel;
    protected $activityLog;
    protected $db;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
        $this->db = \Config\Database::connect();
    }

    // Project Task View (for project_task route)
    public function project_task($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $templates = $this->projectModel->getTaskTemplates();
        $data = [
            'project' => $project,
            'templates' => $templates
        ];
        return $this->template->member('projects/project_task', $data);
    }
    
    public function index()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        $projects = $this->projectModel->getUserProjects($userId);
        
        $data = [
            'title' => 'Projects',
            'projects' => $projects,
            'breadcrumbs' => [
                ['title' => 'Projects']
            ]
        ];
        
        return $this->template->member('projects/index', $data);
    }
    
    public function create()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        if ($this->request->getMethod() === 'POST') {
            // Get lookup IDs for status and priority
            $statusLookup = $this->db->table('status_lookup')
                                   ->where('type', 'project')
                                   ->where('code', $this->request->getPost('status') ?: 'planning')
                                   ->get()->getRowArray();
            $priorityLookup = $this->db->table('priority_lookup')
                                     ->where('type', 'project')
                                     ->where('code', $this->request->getPost('priority') ?: 'medium')
                                     ->get()->getRowArray();
            $projectData = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'budget' => $this->request->getPost('budget'),
                'progress' => 0
            ];
            if ($projectId = $this->projectModel->createProject($projectData)) {
                // Set project status
                if ($statusLookup) {
                    $this->projectModel->setProjectStatus($projectId, $statusLookup['id'], $userId);
                }
                // Set project priority
                if ($priorityLookup) {
                    $this->projectModel->setProjectPriority($projectId, $priorityLookup['id'], $userId);
                }
                // Add creator as project manager
                $this->projectModel->addProjectMember($projectId, $userId, 'manager', $userId);
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'project_created',
                    'table_name' => 'projects',
                    'record_id' => $projectId,
                    'new_values' => json_encode($projectData)
                ]);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project created successfully',
                    'project_id' => $projectId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create project'
                ]);
            }
        }
        return $this->template->member('projects/create');
    }
    
    public function view($id)
    {
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $tasks = $this->taskModel->getKanbanTasks($id);
        $members = $this->userModel->getProjectMembers($id);
        $stats = $this->projectModel->getProjectsWithDetails($id);
        $activities = $this->activityLog->getProjectActivity($id, 10);
        
        $data = [
            'title' => $project['name'],
            'project' => $project,
            'tasks' => $tasks,
            'members' => $members,
            'stats' => $stats,
            'activities' => $activities,
            'breadcrumbs' => [
                ['title' => 'Projects', 'url' => base_url('projects')],
                ['title' => $project['name']]
            ]
        ];
        
        return $this->template->member('projects/view', $data);
    }
    
    public function edit($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        if ($this->request->getMethod() === 'POST') {
            $oldData = $project;
            
            $projectData = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'budget' => $this->request->getPost('budget')
            ];
            
            if ($this->projectModel->updateProject($id, $projectData)) {
                // Update status if provided
                $newStatus = $this->request->getPost('status');
                if ($newStatus) {
                    $statusLookup = $this->db->table('status_lookup')
                                           ->where('type', 'project')
                                           ->where('code', $newStatus)
                                           ->get()->getRowArray();
                    if ($statusLookup) {
                        $this->projectModel->setProjectStatus($id, $statusLookup['id'], $userId);
                    }
                }
                
                // Update priority if provided
                $newPriority = $this->request->getPost('priority');
                if ($newPriority) {
                    $priorityLookup = $this->db->table('priority_lookup')
                                             ->where('type', 'project')
                                             ->where('code', $newPriority)
                                             ->get()->getRowArray();
                    if ($priorityLookup) {
                        $this->projectModel->setProjectPriority($id, $priorityLookup['id'], $userId);
                    }
                }
                
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'project_updated',
                    'table_name' => 'projects',
                    'record_id' => $id,
                    'old_values' => json_encode($oldData),
                    'new_values' => json_encode($projectData)
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update project'
                ]);
            }
        }
        
        return $this->template->member('projects/edit', ['project' => $project]);
    }
    
    public function delete($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project not found'
            ]);
        }
        
        if ($this->projectModel->deleteProject($id)) {
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'project_deleted',
                'table_name' => 'projects',
                'record_id' => $id,
                'old_values' => json_encode($project)
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete project'
            ]);
        }
    }
    
    public function addMember($projectId)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        $memberUserId = $this->request->getPost('user_id');
        $role = $this->request->getPost('role') ?: 'member';
        
        // Check if user is already a member using model method
        if ($this->projectModel->checkProjectMemberExists($projectId, $memberUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User is already a member of this project'
            ]);
        }
        
        // Add member using model method
        if ($this->projectModel->addProjectMember($projectId, $memberUserId, $role, $userId)) {
            $user = $this->userModel->getUserById($memberUserId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'member_added',
                'table_name' => 'project_members',
                'record_id' => $projectId,
                'new_values' => json_encode(['user_id' => $memberUserId, 'role' => $role])
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Member added successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add member'
            ]);
        }
    }
    
    public function removeMember($projectId, $memberUserId)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        // Remove member using model method
        if ($this->projectModel->removeProjectMember($projectId, $memberUserId)) {
            $user = $this->userModel->getUserById($memberUserId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'member_removed',
                'table_name' => 'project_members',
                'record_id' => $projectId,
                'old_values' => json_encode(['user_id' => $memberUserId])
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to remove member'
            ]);
        }
    }

    // AJAX Methods for Projects Index Page
    public function getProjects()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get filter parameters
        $filters = [
            'status' => $this->request->getGet('status'),
            'priority' => $this->request->getGet('priority'),
            'search' => $this->request->getGet('search')
        ];

        // Use model method to get projects with details
        $projects = $this->projectModel->getProjectsWithDetails($userId, $filters);

        return $this->response->setJSON([
            'success' => true,
            'projects' => $projects
        ]);
    }

    public function getProjectStats()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        // Debug info
        log_message('debug', 'Projects::getProjectStats called');
        log_message('debug', 'Session data: ' . json_encode($userData));
        log_message('debug', 'User ID: ' . $userId);
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated',
                'debug' => [
                    'session_data' => $userData,
                    'user_id' => $userId
                ]
            ]);
        }

        // Get projects with full details using the same method as getProjects
        $userProjects = $this->projectModel->getProjectsWithDetails($userId);
        
        $stats = [
            'total' => count($userProjects),
            'completed' => 0,
            'in_progress' => 0,
            'delayed' => 0
        ];

        foreach ($userProjects as $project) {
            // Use the status_code field that should be available from getProjectsWithDetails
            $statusCode = $project['status_code'] ?? $project['status'] ?? 'planning';
            
            switch ($statusCode) {
                case 'completed':
                    $stats['completed']++;
                    break;
                case 'active':
                case 'in_progress':
                case 'ongoing':
                    $stats['in_progress']++;
                    break;
            }
            // Check if project is delayed (past end date and not completed)
            if ($project['end_date'] && strtotime($project['end_date']) < time() && $statusCode !== 'completed') {
                $stats['delayed']++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'debug' => [
                'user_id' => $userId,
                'projects_count' => count($userProjects),
                'sample_project' => !empty($userProjects) ? $userProjects[0] : null
            ]
        ]);
    }

    // Simple test method to verify controller is accessible
    public function test()
    {
        echo "Projects controller is working!";
        exit;
    }

    public function getProject($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project not found'
            ]);
        }
        // Add owner name
        $owner = $this->userModel->getUserById($project['owner_id'] ?? 0);
        $project['owner_name'] = $owner ? (($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown';

        // Always provide status and priority fields for frontend
        $project['status'] = $project['status_name'] ?? 'unknown';
        $project['priority'] = $project['priority_name'] ?? 'medium';
        // Optionally add color fields if needed by frontend
        $project['status_color'] = $project['status_color'] ?? null;
        $project['priority_color'] = $project['priority_color'] ?? null;

        // Ensure progress is always set
        if (!isset($project['progress'])) {
            $project['progress'] = 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'project' => $project
        ]);
    }

    // AJAX: Get project stats for view page
    public function getStats($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }
        $stats = $this->projectModel->getProjectStats($id);
        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats
        ]);
    }

    // AJAX: Get recent activity for a project
    public function recentActivity($id)
    {
        $activities = $this->activityLog->getProjectActivity($id, 10);
        return $this->response->setJSON([
            'success' => true,
            'activities' => $activities
        ]);
    }

    // AJAX: Get all tasks for a project
    public function tasks($id)
    {
        $tasks = $this->taskModel->getTasksWithDetails($id);
        // Ensure each task has status and priority fields for frontend
        foreach ($tasks as &$task) {
            $task['status'] = $task['status_name'] ?? 'todo';
            $task['priority'] = $task['priority_name'] ?? 'medium';
            // Optionally add assignee_name if not present
            if (!isset($task['assignee_name'])) {
                $task['assignee_name'] = ($task['owner_first_name'] ?? '') . ' ' . ($task['owner_last_name'] ?? '');
                $task['assignee_name'] = trim($task['assignee_name']) ?: null;
            }
        }
        unset($task);
        return $this->response->setJSON([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    // AJAX: Get all members for a project
    public function members($id)
    {
        $members = $this->projectModel->getProjectMembers($id);
        // Format for select options
        $formatted = array_map(function($m) {
            return [
                'user_id' => $m['user_id'],
                'first_name' => $m['first_name'],
                'last_name' => $m['last_name']
            ];
        }, $members);
        return $this->response->setJSON([
            'success' => true,
            'members' => $formatted
        ]);
    }

    // AJAX: Progress chart data
    public function progressData($id)
    {
        $data = $this->projectModel->getProgressChartData($id);
        return $this->response->setJSON([
            'success' => true,
            'chartData' => $data
        ]);
    }

    // AJAX: Task distribution chart data
    public function taskDistribution($id)
    {
        $data = $this->projectModel->getTaskDistributionData($id);
        return $this->response->setJSON([
            'success' => true,
            'chartData' => $data
        ]);
    }

    // Get all task templates (AJAX)
    public function get_task_templates()
    {
        $templates = $this->projectModel->getTaskTemplates();
        foreach ($templates as &$tmpl) {
            $tmpl['progress'] = $this->projectModel->getTemplateProgress($tmpl['code']);
        }
        unset($tmpl);
        return $this->response->setJSON([
            'success' => true,
            'templates' => $templates
        ]);
    }

    // Dynamic task page for a template (Excel-like flexibility)
    public function task_page($template_code)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to(base_url('auth/login'));
        }

        // Get project_id from query string (or route param if you prefer)
        $project_id = $this->request->getGet('project_id');
        if (!$project_id) {
            // Optionally, redirect or show error if project_id is missing
            return redirect()->to(base_url('projects'));
        }

        // Fetch template by code
        $template = $this->projectModel->getTaskTemplateByCode($template_code);

        $fields = [];
        if ($template && !empty($template['fields'])) {
            $fields = json_decode($template['fields'], true);
            if (!is_array($fields)) $fields = [];
        }

        // Fetch tasks for this template and project
        $tasks = $this->projectModel->getTasksByTemplateAndProject($template_code, $project_id);

        return $this->template->member('projects/task_dynamic', [
            'template' => $template,
            'fields' => $fields,
            'tasks' => $tasks,
            'project_id' => $project_id
        ]);
    // Add new task (show form for dynamic template)
    }

    public function add_task()
    {
        $template_code = $this->request->getGet('template');
        $template = $this->projectModel->getTaskTemplateByCode($template_code);
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template not found'
            ]);
        }
        $fields = json_decode($template['fields'] ?? '[]', true);
        $data = [
            'template' => $template,
            'fields' => $fields
        ];
        return $this->template->member('projects/add_task', $data);
    }

    // Autosave task (AJAX, dynamic fields)
    public function save_task()
    {
        $taskId = $this->request->getPost('id');
        $template_code = $this->request->getPost('template_code');
        pr($this->request->getPost());
        $project_id = $this->request->getPost('project_id');
        $template = $this->projectModel->getTaskTemplateByCode($template_code);
        // pr($template);
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template not found'
            ]);
        }
        $fields = json_decode($template['fields'] ?? '[]', true);
        $taskData = [];
        // pr($fields);
        foreach ($fields as $field) {
            $taskData[$field] = $this->request->getPost($field);
        }
        if (!$taskId) {
            // Insert new task
            $newId = $this->projectModel->insertDynamicTask([
                'project_id' => $project_id,
                'template_id' => $template['id'],
                'data' => json_encode($taskData)
            ]);
            return $this->response->setJSON([
                'success' => !!$newId,
                'task_id' => $newId
            ]);
        } else {
            // Update existing task
            $result = $this->projectModel->autosaveTask($taskId, [
                'template_code' => $template_code,
                'data' => json_encode($taskData)
            ]);
            return $this->response->setJSON([
                'success' => $result
            ]);
        }
    }

    // Project list view
    public function project_list()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        $projects = $this->projectModel->getUserProjects($userId);
        $status_options = $this->db->table('status_lookup')->where('type', 'project')->get()->getResultArray();
        $priority_options = $this->db->table('priority_lookup')->where('type', 'project')->get()->getResultArray();
        $status_colors = [
            'pending' => 'warning',
            'in_progress' => 'primary',
            'review' => 'info',
            'completed' => 'success'
        ];
        $priority_colors = [
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger'
        ];
        $data = [
            'projects' => $projects,
            'status_options' => $status_options,
            'priority_options' => $priority_options,
            'status_colors' => $status_colors,
            'priority_colors' => $priority_colors
        ];
        return $this->template->member('projects/project_list', $data);
    }

        // AJAX: Delete a dynamic task row
    public function delete_task()
    {
        $taskId = $this->request->getPost('id');
        if (!$taskId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No task ID provided.'
            ]);
        }
        $result = $this->projectModel->deleteTaskById($taskId);
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Task deleted.' : 'Failed to delete task.'
        ]);
    }
}