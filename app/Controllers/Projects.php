<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Projects extends BaseController
{

    // AJAX: Get all tasks for a template and project (for dynamic progress bar)
    public function get_tasks_by_template($template_code, $project_id)
    {
        // Security: check user session
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }
        // Fetch tasks for this template and project
        $tasks = $this->projectModel->getTasksByTemplateAndProject($template_code, $project_id);
        return $this->response->setJSON([
            'success' => true,
            'tasks' => $tasks
        ]);
    }
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

    // AJAX: Get all users for a project (for dynamic task dropdowns)
    public function project_users($projectId)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }
        // Use the helper or model to get all users for this project
        if (!function_exists('get_project_users')) {
            helper('general');
        }
        $users = get_project_users($projectId);
        // Format for select options
        $formatted = array_map(function($u) {
            return [
                'user_id' => $u['user_id'],
                'first_name' => $u['first_name'],
                'last_name' => $u['last_name']
            ];
        }, $users);
        return $this->response->setJSON([
            'success' => true,
            'users' => $formatted
        ]);
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
            $statusCode = $this->request->getPost('status') ?: 'planning';
            $statusLookup = $this->projectModel->getProjectStatusByCode($statusCode);
            $projectData = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'budget' => $this->request->getPost('budget'),
            ];
            // pr($projectData);
            if ($projectId = $this->projectModel->createProject($projectData)) {
                // Set project status using model
                if ($statusLookup) {
                    $this->projectModel->setProjectStatusById($projectId, $statusLookup['id'], $userId);
                }
                // Add creator as project manager
                $this->projectModel->addProjectMember($projectId, $userId, 'manager', $userId);
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'project_created',
                    'details' => json_encode([
                        'project_id' => $projectId,
                        'data' => $projectData
                    ])
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
        $activities = $this->activityLog->getActivityLogs([
            'action' => 'project_created',
            'limit' => 10
        ]);
        
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
                // Update status if provided using model
                $newStatus = $this->request->getPost('status');
                if ($newStatus) {
                    $statusLookup = $this->projectModel->getProjectStatusByCode($newStatus);
                    if ($statusLookup) {
                        $this->projectModel->setProjectStatusById($id, $statusLookup['id'], $userId);
                    }
                }
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'project_updated',
                    'details' => json_encode([
                        'project_id' => $id,
                        'old' => $oldData,
                        'new' => $projectData
                    ])
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
                'details' => json_encode([
                    'project_id' => $id,
                    'old' => $project
                ])
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
                'details' => json_encode([
                    'project_id' => $projectId,
                    'user_id' => $memberUserId,
                    'role' => $role
                ])
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
                'details' => json_encode([
                    'project_id' => $projectId,
                    'user_id' => $memberUserId
                ])
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
        $project['owner_name'] = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown';

        // Calculate progress as in project_list (index): (completed_tasks / total_tasks) * 100
        $taskBuilder = $this->db->table('tasks t');
        $taskBuilder->select('COUNT(*) as total_tasks');
        $taskBuilder->where('t.project_id', $project['id']);
        $taskBuilder->where('t.is_delete', 0);
        $taskStats = $taskBuilder->get()->getRowArray();

        $completedBuilder = $this->db->table('tasks t');
        $completedBuilder->select('COUNT(*) as completed_tasks');
        $completedBuilder->join('task_status ts', 'ts.task_id = t.id AND ts.is_active = 1 AND ts.is_delete = 0', 'left');
        $completedBuilder->join('status_lookup sl', 'sl.id = ts.status_id AND sl.code = "completed" AND sl.is_delete = 0', 'left');
        $completedBuilder->where('t.project_id', $project['id']);
        $completedBuilder->where('t.is_delete', 0);
        $completedBuilder->where('sl.id IS NOT NULL');
        $completedStats = $completedBuilder->get()->getRowArray();

        $totalTasks = (int)$taskStats['total_tasks'];
        $completedTasks = (int)$completedStats['completed_tasks'];
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        $project['progress'] = $progress;
        $project['total_tasks'] = $totalTasks;
        $project['completed_tasks'] = $completedTasks;

        // Ensure all fields are set and never null/empty for frontend
        $project['name'] = $project['name'] ?? 'Untitled';
        $project['description'] = $project['description'] ?? '';
        $project['start_date'] = $project['start_date'] ?? 'N/A';
        $project['end_date'] = $project['end_date'] ?? 'N/A';
        $project['budget'] = $project['budget'] ?? 'N/A';
        $project['client'] = $project['client'] ?? 'N/A';
        $project['status'] = $project['status_name'] ?? 'Unknown';
        $project['status_color'] = $project['status_color'] ?? '#e2e8f0';

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
        // Ensure each task has status and priority fields for frontend, and decode task fields from data JSON
        foreach ($tasks as &$task) {
            $task['status'] = $task['status_name'] ?? 'todo';
            $task['priority'] = $task['priority_name'] ?? 'medium';
            // Optionally add assignee_name if not present
            if (!isset($task['assignee_name'])) {
                $task['assignee_name'] = ($task['owner_first_name'] ?? '') . ' ' . ($task['owner_last_name'] ?? '');
                $task['assignee_name'] = trim($task['assignee_name']) ?: null;
            }
            // Ensure task fields are available at top level for frontend
            if (isset($task['data'])) {
                $dataArr = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
                if (is_array($dataArr)) {
                    foreach ($dataArr as $k => $v) {
                        if (!isset($task[$k])) {
                            $task[$k] = $v;
                        }
                    }
                }
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

    // get_task_templates task page for a template (Excel-like flexibility)
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
        $headerMap = [];
        $all_headers = [];
        if ($template && !empty($template['fields'])) {
            $fields = json_decode($template['fields'], true);
            if (!is_array($fields)) $fields = [];
            // Fetch all headers
            $all_headers = $this->db->table('task_headers')->where('is_active', 1)->where('is_delete', 0)->get()->getResultArray();
            foreach ($all_headers as $h) {
                $headerMap[$h['id']] = $h['column_name'];
            }
        }

        // Fetch tasks for this template and project
        $tasks = $this->projectModel->getTasksByTemplateAndProject($template_code, $project_id);

        return $this->template->member('projects/task_dynamic', [
            'template' => $template,
            'fields' => $fields,
            'all_headers' => $all_headers,
            'headerMap' => $headerMap,
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
        $project_id = $this->request->getPost('project_id');
        $template = $this->projectModel->getTaskTemplateByCode($template_code);
        // pr($template);
        // pr($this->request->getPost());
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template not found'
            ]);
        }
        $fields = json_decode($template['fields'] ?? '[]', true);
        $taskData = [];
        // Map POST keys with underscores to template field names with spaces
        $postData = $this->request->getPost();
        foreach ($fields as $field) {
            // Normalize field name: replace spaces with underscores, remove casing
            $normalized = str_replace(' ', '_', $field);
            // Try exact match first
            if (isset($postData[$field])) {
                $taskData[$field] = $postData[$field];
            } elseif (isset($postData[$normalized])) {
                $taskData[$field] = $postData[$normalized];
            } else {
                // Try lowercased
                $lower = strtolower($normalized);
                if (isset($postData[$lower])) {
                    $taskData[$field] = $postData[$lower];
                } else {
                    $taskData[$field] = null;
                }
            }
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
        foreach ($projects as &$project) {
            $project['task_count'] = $this->taskModel->countTasksByProject($project['id']);
            // Calculate average progress from 'progress' field in decoded data JSON of all tasks in this project
            $tasks = $this->taskModel->getTasksWithDetails($project['id']);
            $progressSum = 0;
            $progressCount = 0;
            foreach ($tasks as &$task) {
                // Merge data JSON fields into top-level task array
                if (isset($task['data'])) {
                    $dataArr = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
                    if (is_array($dataArr)) {
                        foreach ($dataArr as $k => $v) {
                            if (!isset($task[$k])) {
                                $task[$k] = $v;
                            }
                        }
                    }
                }
                $progress = null;
                // Prefer 'progress' field, fallback to 'Progress' (case-insensitive)
                if (isset($task['progress'])) {
                    $progress = $task['progress'];
                } elseif (isset($task['Progress'])) {
                    $progress = $task['Progress'];
                }
                if ($progress !== null) {
                    $progressRaw = trim($progress);
                    $progressRaw = rtrim($progressRaw, '%');
                    $progressRaw = trim($progressRaw);
                    if ($progressRaw !== '' && is_numeric($progressRaw)) {
                        $progressSum += floatval($progressRaw);
                        $progressCount++;
                    }
                }
            }
            unset($task);
            $project['avg_progress'] = $progressCount > 0 ? ($progressSum / $progressCount) : 0;
        }
        unset($project);
        $status_options = $this->db->table('status_lookup')->where('type', 'project')->get()->getResultArray();
        $status_colors = [
            'pending' => 'warning',
            'in_progress' => 'primary',
            'review' => 'info',
            'completed' => 'success'
        ];
        $data = [
            'projects' => $projects,
            'status_options' => $status_options,
            'status_colors' => $status_colors,
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

    // AJAX endpoint to update task order after drag-and-drop
    public function update_task_order()
    {
        $taskOrder = $this->request->getPost('task_order'); // expects array of task IDs
        if (!is_array($taskOrder)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid order data'
            ]);
        }
        $result = $this->projectModel->updateTaskOrder($taskOrder);
        return $this->response->setJSON([
            'success' => $result
        ]);
    }

    /**
     * AJAX endpoint to update task template headers (fields)
     */
    public function updateHeaders()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        $fields = $this->request->getJSON(true)['fields'] ?? null;
        $templateId = $this->request->getVar('template_id');
        if (!$fields || !is_array($fields)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No fields provided']);
        }
        if (!$templateId) {
            return $this->response->setJSON(['success' => false, 'message' => 'No template ID']);
        }
        $projectModel = model('ProjectModel');
        $headerIds = [];
        foreach ($fields as $field) {
            // Check if header exists
            $existing = $projectModel->db->table('task_headers')
                ->where('column_name', $field)
                ->where('is_active', 1)
                ->where('is_delete', 0)
                ->get()->getRowArray();
            if ($existing) {
                $headerIds[] = $existing['id'];
            } else {
                $headerIds[] = $projectModel->insertTaskHeader($field);
            }
        }
        // Update fields column in task_templates
        $fieldsJson = json_encode(array_values($headerIds));
        $result = $projectModel->db->table('task_templates')->where('id', $templateId)->update(['fields' => $fieldsJson]);
        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update headers']);
        }
    }

}