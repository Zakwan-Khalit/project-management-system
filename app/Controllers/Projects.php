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

        $templates = $this->projectModel->getTaskTemplatesByProject($id);
        $data = [
            'project' => $project,
            'templates' => $templates
        ];
        return $this->template->member('projects/project_task', $data);
    }
    
    public function index()
    {
        // pr(session('userdata'));
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
                'last_name' => $m['last_name'],
                'role' => $m['role'],
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
        $projectId = $this->request->getGet('project_id');
        $templates = $this->projectModel->getTaskTemplatesByProject($projectId);
        return $this->response->setJSON(['success' => true, 'templates' => $templates]);
    }

    // get_task_templates task page for a template (Excel-like flexibility)
    public function task_page($templateParam)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        $project_id = $this->request->getGet('project_id');
       
        // templateParam can be either 'template=ID' or just the ID
        $templateId = null;
        if (is_numeric($templateParam)) {
            $templateId = intval($templateParam);
        } elseif (strpos($templateParam, 'template=') === 0) {
            $templateId = intval(substr($templateParam, 9));
        }
        
        // Fetch template by ID and project
        $template = $this->projectModel->getTaskTemplateById($templateId, $project_id);

        $fields = [];
        $headerMap = [];
        $all_headers = [];
        $header_lookup = [];
        if ($template && !empty($template['fields'])) {
            $fields = json_decode($template['fields'], true);
            if (!is_array($fields)) $fields = [];
            // Fetch all headers
            $all_headers = $this->db->table('task_headers')->where('is_active', 1)->where('is_delete', 0)->get()->getResultArray();
            foreach ($all_headers as $h) {
                $headerMap[$h['id']] = $h['column_name'];
            }
        }
        // Always fetch header_lookup for dropdown
        $header_lookup = $this->projectModel->getHeaderLookupOptions();

        // Fetch tasks for this template and project
        $tasks = $this->projectModel->getTasksByTemplateIdAndProject($templateId, $project_id);
        // Decode Progress from data JSON for each task
        foreach ($tasks as &$task) {
            $dataArr = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
            if (is_array($dataArr)) {
                foreach ($dataArr as $k => $v) {
                    $task[$k] = $v;
                }
            }
        }
        unset($task);

        return $this->template->member('projects/task_dynamic', [
            'template' => $template,
            'fields' => $fields,
            'all_headers' => $all_headers,
            'headerMap' => $headerMap,
            'tasks' => $tasks,
            'project_id' => $project_id,
            'template_id' => $templateId,
            'header_lookup' => $header_lookup
        ]);
    }

    public function add_task()
    {
        $template_id = $this->request->getGet('template_id');
        $project_id = $this->request->getGet('project_id');
        $template = $this->projectModel->getTaskTemplateById($template_id, $project_id);
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template not found'
            ]);
        }
        $fields = json_decode($template['fields'] ?? '[]', true);
        $data = [
            'template' => $template,
            'fields' => $fields,
            'project_id' => $project_id,
            'template_id' => $template_id
        ];
        return $this->template->member('projects/add_task', $data);
    }

    // Autosave task (AJAX, dynamic fields)
    public function save_task()
    {
        $taskId = $this->request->getPost('id');
        $template_id = $this->request->getPost('template_id');
        $project_id = $this->request->getPost('project_id');
        $template = $this->projectModel->getTaskTemplateById($template_id, $project_id);
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template not found'
            ]);
        }
        $fields = json_decode($template['fields'] ?? '[]', true);
        $taskData = [];
        $postData = $this->request->getPost();
        // Accept header IDs as keys directly from POST data
        foreach ($fields as $headerId) {
            if (isset($postData[$headerId])) {
                $taskData[$headerId] = $postData[$headerId];
            } else {
                $taskData[$headerId] = null;
            }
        }
        if (!$taskId) {
            $newId = $this->projectModel->insertDynamicTask([
                'project_id' => $project_id,
                'template_id' => $template_id,
                'data' => json_encode($taskData)
            ]);
            return $this->response->setJSON([
                'success' => !!$newId,
                'task_id' => $newId
            ]);
        } else {
            $result = $this->projectModel->autosaveTask($taskId, [
                'template_id' => $template_id,
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
        $result = $projectModel->updateTaskTemplateFields($templateId, $fields);
        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update headers']);
        }
    }

    public function create_template()
    {
        $name = $this->request->getPost('name');
        $projectId = $this->request->getPost('project_id');
        if (!$name || !$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data']);
        }
        $templateId = $this->projectModel->createTaskTemplate($projectId, $name);
        if ($templateId) {
            return $this->response->setJSON(['success' => true, 'id' => $templateId]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to create template']);
    }
    
    /**
     * Add a new task header and return its ID (for dynamic table headers)
     * Accepts POST (or GET for dev), expects 'column_name' param
     * Returns: { success: true, id: <header_id> } or { success: false, message: ... }
     */
    public function add_task_header()
    {
        $columnName = $this->request->getPost('column_name') ?? $this->request->getGet('column_name');
        if (!$columnName) {
            return $this->response->setJSON(['success' => false, 'message' => 'No column name provided']);
        }
        $id = $this->projectModel->insertTaskHeader($columnName);
        if ($id) {
            return $this->response->setJSON(['success' => true, 'id' => $id]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add header']);
        }
    }

    /**
     * AJAX: Add multiple project members (from modal, department+users)
     * POST: project_id, department_id, user_ids[]
     * Returns: { success, added, failed }
     */
    public function add_project_members()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        $userData = session('userdata');
        $assignedBy = $userData['id'] ?? null;
        $projectId = $this->request->getPost('project_id');
        $departmentId = $this->request->getPost('department_id');
        $userIds = $this->request->getPost('user_ids');
        if (!$projectId || !$userIds || !is_array($userIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data']);
        }
        $result = $this->projectModel->addProjectMembersBulk($projectId, $userIds, $departmentId, 'member', $assignedBy);
        return $this->response->setJSON($result);
    }

    /**
     * API: Get all departments for dropdown
     */
    public function departments()
    {
        $departments = $this->projectModel->getDepartments();
        return $this->response->setJSON([
            'success' => true,
            'departments' => $departments
        ]);
    }

    /**
     * API: Get users by department for dropdown (exclude users already in the project)
     */
    public function departmentUsers($departmentId)
    {
        $projectId = $this->request->getGet('project_id');
        $model = new \App\Models\ProjectModel();
        $users = $model->getUsersByDepartment($departmentId, $projectId);
        return $this->response->setJSON([
            'success' => true,
            'users' => $users
        ]);
    }

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
        // Fetch tasks for this template and project (by template ID)
        $tasks = $this->projectModel->getTasksByTemplateIdAndProject($template_code, $project_id); // $template_code is now template_id
        // Decode progress from data JSON for each task
        foreach ($tasks as &$task) {
            $dataArr = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
            if (is_array($dataArr)) {
                foreach ($dataArr as $k => $v) {
                    $task[$k] = $v;
                }
            }
        }
        unset($task);
        return $this->response->setJSON([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    // AJAX: Get all scopes for a project
    public function get_project_scopes()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $projectId = $this->request->getGet('project_id');
        if (!$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID required']);
        }

        $scopes = $this->projectModel->getProjectScopes($projectId);
        
        return $this->response->setJSON([
            'success' => true,
            'scopes' => $scopes
        ]);
    }

    // AJAX: Create new scope
    public function create_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $projectId = $this->request->getPost('project_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        if (!$projectId || !$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID and scope name are required']);
        }

        $scopeId = $this->projectModel->createScope($projectId, $name, $description);
        
        if ($scopeId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Scope created successfully',
                'scope_id' => $scopeId
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create scope']);
        }
    }

    // AJAX: Update scope
    public function update_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $scopeId = $this->request->getPost('scope_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        if (!$scopeId || !$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Scope ID and name are required']);
        }

        $result = $this->projectModel->updateScope($scopeId, $name, $description);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Scope updated successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update scope']);
        }
    }

    // AJAX: Delete scope
    public function delete_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $scopeId = $this->request->getPost('scope_id');

        if (!$scopeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Scope ID is required']);
        }

        $result = $this->projectModel->deleteScope($scopeId);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Scope deleted successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete scope']);
        }
    }

    // AJAX: Update template order within scope
    public function update_template_order()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $templateOrderJson = $this->request->getPost('template_order');
        $scopeId = $this->request->getPost('scope_id');
        $projectId = $this->request->getPost('project_id');

        if (!$templateOrderJson || !$scopeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid template order data']);
        }

        // Parse JSON if it's a string
        $templateOrder = is_string($templateOrderJson) ? json_decode($templateOrderJson, true) : $templateOrderJson;
        
        if (!is_array($templateOrder)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid template order format']);
        }

        $result = $this->projectModel->updateTemplateOrder($templateOrder, $scopeId);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Template order updated successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update template order']);
        }
    }

    // AJAX: Add template to scope
    public function add_template_to_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $scopeId = $this->request->getPost('scope_id');
        $templateIds = $this->request->getPost('template_ids');
        $projectId = $this->request->getPost('project_id');

        if (!is_array($templateIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Template IDs must be an array']);
        }

        // Handle both adding to scope (scopeId provided) and removing from scope (scopeId is null)
        $result = $this->projectModel->addTemplatesToScope($scopeId, $templateIds);
        
        if ($result) {
            $message = $scopeId ? 'Templates added to scope successfully' : 'Templates removed from scope successfully';
            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);
        } else {
            $message = $scopeId ? 'Failed to add templates to scope' : 'Failed to remove templates from scope';
            return $this->response->setJSON(['success' => false, 'message' => $message]);
        }
    }

    // AJAX: Add custom templates to scope
    public function add_custom_template_to_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $scopeId = $this->request->getPost('scope_id');
        $projectId = $this->request->getPost('project_id');
        $componentsJson = $this->request->getPost('components');

        if (!$scopeId || !$projectId || !$componentsJson) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        $components = json_decode($componentsJson, true);
        if (!is_array($components) || empty($components)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No components provided']);
        }

        try {
            $createdTemplates = [];
            
            // Get the next order number for this scope
            $builder = $this->db->table('task_templates');
            $builder->selectMax('component_order');
            $builder->where('scope_id', $scopeId);
            $builder->where('is_delete', 0);
            $result = $builder->get()->getRowArray();
            $nextOrder = ($result['component_order'] ?? 0) + 1;

            foreach ($components as $component) {
                $templateName = $component['name'] ?? '';
                $templateType = $component['type'] ?? 'custom';

                if (empty($templateName)) {
                    continue;
                }

                // Create basic task template structure based on the template name
                $fields = $this->generateTemplateFields($templateName, $templateType);

                $templateData = [
                    'project_id' => $projectId,
                    'scope_id' => $scopeId,
                    'name' => $templateName,
                    'fields' => json_encode($fields),
                    'component_order' => $nextOrder++,
                    'is_active' => 1,
                    'is_delete' => 0,
                    'date_created' => date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s')
                ];

                $this->db->table('task_templates')->insert($templateData);
                $templateId = $this->db->insertID();
                
                if ($templateId) {
                    $createdTemplates[] = [
                        'id' => $templateId,
                        'name' => $templateName,
                        'type' => $templateType
                    ];
                }
            }

            if (!empty($createdTemplates)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Components added successfully',
                    'templates' => $createdTemplates
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No components were created']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding custom templates to scope: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add components']);
        }
    }

    // Generate template fields based on template name and type
    private function generateTemplateFields($templateName, $templateType)
    {
        // Default fields that all templates will have
        $defaultFields = [
            [
                'name' => 'Task',
                'type' => 'text',
                'required' => true,
                'description' => 'Task description'
            ],
            [
                'name' => 'Status',
                'type' => 'select',
                'required' => true,
                'options' => ['Not Started', 'In Progress', 'Completed', 'On Hold'],
                'description' => 'Current status of the task'
            ],
            [
                'name' => 'Priority',
                'type' => 'select',
                'required' => false,
                'options' => ['Low', 'Medium', 'High', 'Critical'],
                'description' => 'Task priority level'
            ],
            [
                'name' => 'Assigned To',
                'type' => 'text',
                'required' => false,
                'description' => 'Person responsible for the task'
            ],
            [
                'name' => 'Due Date',
                'type' => 'date',
                'required' => false,
                'description' => 'Expected completion date'
            ],
            [
                'name' => 'Progress',
                'type' => 'percentage',
                'required' => false,
                'description' => 'Completion percentage'
            ]
        ];

        // Add specific fields based on template type
        $specificFields = [];
        
        $lowerName = strtolower($templateName);
        
        if (strpos($lowerName, 'requirement') !== false || strpos($lowerName, 'specification') !== false) {
            $specificFields = [
                [
                    'name' => 'Requirement ID',
                    'type' => 'text',
                    'required' => true,
                    'description' => 'Unique requirement identifier'
                ],
                [
                    'name' => 'Category',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Functional', 'Non-Functional', 'Business', 'Technical'],
                    'description' => 'Requirement category'
                ],
                [
                    'name' => 'Complexity',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Simple', 'Medium', 'Complex'],
                    'description' => 'Implementation complexity'
                ]
            ];
        } elseif (strpos($lowerName, 'testing') !== false || strpos($lowerName, 'qa') !== false) {
            $specificFields = [
                [
                    'name' => 'Test Case ID',
                    'type' => 'text',
                    'required' => true,
                    'description' => 'Unique test case identifier'
                ],
                [
                    'name' => 'Test Type',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Unit', 'Integration', 'System', 'Acceptance', 'Performance', 'Security'],
                    'description' => 'Type of testing'
                ],
                [
                    'name' => 'Test Result',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Pass', 'Fail', 'Blocked', 'Not Tested'],
                    'description' => 'Test execution result'
                ]
            ];
        } elseif (strpos($lowerName, 'development') !== false || strpos($lowerName, 'coding') !== false) {
            $specificFields = [
                [
                    'name' => 'Feature/Module',
                    'type' => 'text',
                    'required' => false,
                    'description' => 'Feature or module being developed'
                ],
                [
                    'name' => 'Technology',
                    'type' => 'text',
                    'required' => false,
                    'description' => 'Technology/framework used'
                ],
                [
                    'name' => 'Code Review Status',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Pending', 'In Review', 'Approved', 'Needs Changes'],
                    'description' => 'Code review status'
                ]
            ];
        } elseif (strpos($lowerName, 'design') !== false || strpos($lowerName, 'documentation') !== false) {
            $specificFields = [
                [
                    'name' => 'Document Type',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Technical Spec', 'User Guide', 'API Doc', 'Design Doc', 'Architecture'],
                    'description' => 'Type of document'
                ],
                [
                    'name' => 'Review Status',
                    'type' => 'select',
                    'required' => false,
                    'options' => ['Draft', 'Under Review', 'Approved', 'Published'],
                    'description' => 'Document review status'
                ]
            ];
        }

        // Merge default and specific fields
        return array_merge($defaultFields, $specificFields);
    }

    // AJAX: Update component/template name
    public function update_component_name()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $templateId = $this->request->getPost('template_id');
        $name = $this->request->getPost('name');
        $projectId = $this->request->getPost('project_id');

        if (!$templateId || !$name || !$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        try {
            $result = $this->db->table('task_templates')
                ->where('id', $templateId)
                ->where('project_id', $projectId)
                ->update([
                    'name' => trim($name),
                    'date_modified' => date('Y-m-d H:i:s')
                ]);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Component name updated successfully'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update component name']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating component name: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update component name']);
        }
    }

    // AJAX: Soft delete component (set is_active=0, is_delete=1)
    public function soft_delete_component()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $templateId = $this->request->getPost('template_id');
        $projectId = $this->request->getPost('project_id');

        if (!$templateId || !$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        try {
            $result = $this->db->table('task_templates')
                ->where('id', $templateId)
                ->where('project_id', $projectId)
                ->update([
                    'is_active' => 0,
                    'is_delete' => 1,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Component deleted successfully'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete component']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting component: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete component']);
        }
    }
}