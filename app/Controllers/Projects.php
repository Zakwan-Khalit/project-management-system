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
                'full_name' => $u['full_name']
            ];
        }, $users);
        return $this->response->setJSON([
            'success' => true,
            'users' => $formatted
        ]);
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
            'projects' => $projects
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
                'cost' => $this->request->getPost('cost'),
                'client' => $this->request->getPost('client'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
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
                'cost' => $this->request->getPost('cost'),
                'client' => $this->request->getPost('client'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
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
        $fte = $this->request->getPost('fte');
        $endDateInvolvement = $this->request->getPost('end_date_involvement');
        
        // Check if user is already a member using model method
        if ($this->projectModel->checkProjectMemberExists($projectId, $memberUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User is already a member of this project'
            ]);
        }
        
    // Add member using model method
    if ($this->projectModel->addProjectMember($projectId, $memberUserId, $role, $userId, $fte, $endDateInvolvement)) {
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
        $project['owner_name'] = $owner ? ($owner['full_name'] ?? 'Unknown') : 'Unknown';

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

        // --- Calculate average progress from JSON data in tasks (as in getUserProjects) ---
        $taskDataBuilder = $this->db->table('tasks');
        $taskDataBuilder->select('data');
        $taskDataBuilder->where('project_id', $project['id']);
        $taskDataBuilder->where('is_delete', 0);
        $tasks = $taskDataBuilder->get()->getResultArray();

        $progressSum = 0;
        $progressCount = 0;
        foreach ($tasks as $task) {
            $data = json_decode($task['data'], true);
            if (is_array($data)) {
                foreach ($data as $value) {
                    if (is_string($value) && strpos($value, '%') !== false) {
                        $progressVal = trim($value);
                        $progressVal = rtrim($progressVal, '%');
                        if (is_numeric($progressVal)) {
                            $progressSum += floatval($progressVal);
                            $progressCount++;
                            break; // Only count one progress value per task
                        }
                    }
                }
            }
        }
        $project['avg_progress'] = $progressCount > 0 ? ($progressSum / $progressCount) : 0;

        // Ensure all fields are set and never null/empty for frontend
        $project['name'] = $project['name'] ?? 'Untitled';
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
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $activities = $this->activityLog->getProjectActivity($id, 15);
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
                $task['assignee_name'] = $task['owner_full_name'] ?? null;
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
                'full_name' => $m['full_name'],
                'role' => $m['role'],
                'fte' => $m['fte'],
                'end_date_involvement' => $m['end_date_involvement'],
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
            // Progress is now calculated in the model's getUserProjects method
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
        $positionId = $this->request->getPost('position_id');
        $userIds = $this->request->getPost('user_ids');
        $fte = $this->request->getPost('fte');
        $endDateInvolvement = $this->request->getPost('end_date_involvement');
        if (!$projectId || !$userIds || !is_array($userIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data']);
        }
        // Support both department_id and position_id for backwards compatibility
        $referenceId = $positionId ?? $departmentId;
        $referenceType = $positionId ? 'position' : 'department';
        $result = $this->projectModel->addProjectMembersBulk($projectId, $userIds, $referenceId, 'member', $assignedBy, $referenceType, $fte, $endDateInvolvement);
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

    public function positions()
    {
        $positions = $this->projectModel->getPositions();
        return $this->response->setJSON([
            'success' => true,
            'positions' => $positions
        ]);
    }

    public function positionUsers($positionId)
    {
        $projectId = $this->request->getGet('project_id');
        $model = new \App\Models\ProjectModel();
        $users = $model->getUsersByPosition($positionId, $projectId);
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

    /**
     * Remove a team member from a project (soft delete)
     */
    public function remove_project_member()
    {
        try {
            // Check if user is authenticated
            $userData = session('userdata');
            $currentUserId = $userData['id'] ?? null;
            if (!$currentUserId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Get POST data
            $projectId = $this->request->getPost('project_id');
            $userId = $this->request->getPost('user_id');

            // Validate required fields
            if (!$projectId || !$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID and User ID are required'
                ]);
            }

            // Check if the current user has permission to remove team members
            // You can add role-based permission checks here if needed
            
            // Remove the team member using the model
            $result = $this->projectModel->removeProjectMember($projectId, $userId);

            if ($result) {
                // Log the activity
                $this->activityLog->insert([
                    'user_id' => $currentUserId,
                    'project_id' => $projectId,
                    'action' => 'removed_team_member',
                    'description' => "Removed team member from project",
                    'is_active' => 1,
                    'is_delete' => 0,
                    'date_created' => date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s')
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Team member removed successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove team member'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error removing team member: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while removing the team member'
            ]);
        }
    }

    /**
     * AJAX: Update FTE and End Date Involvement for a project member
     * POST: project_id, user_id, fte, end_date_involvement
     * Returns: { success, message }
     */
    public function update_project_member()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        $projectId = $this->request->getPost('project_id');
        $userId = $this->request->getPost('user_id');
        $fte = $this->request->getPost('fte');
        $endDateInvolvement = $this->request->getPost('end_date_involvement');
        if (!$projectId || !$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data']);
        }
        $result = $this->projectModel->updateProjectMember($projectId, $userId, $fte, $endDateInvolvement);
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Member updated']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update member']);
        }
    }

    // Get a single project member's data for AJAX edit modal
    public function get_project_member($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['error' => 'No user ID provided'])->setStatusCode(400);
        }
        $projectId = $this->request->getGet('project_id');
        $projectModel = new \App\Models\ProjectModel();
        $member = $projectModel->getProjectMemberByUserId($userId, $projectId);
        if (!$member) {
            return $this->response->setJSON(['error' => 'Member not found'])->setStatusCode(404);
        }
        return $this->response->setJSON($member);
    }
}