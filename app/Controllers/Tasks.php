<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Tasks extends BaseController
{
    protected $taskModel;
    protected $projectModel;
    protected $userModel;
    protected $activityLog;
    protected $statusLookupModel;
    protected $priorityLookupModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
        $this->statusLookupModel = new \App\Models\StatusLookupModel();
        $this->priorityLookupModel = new \App\Models\PriorityLookupModel();
    }
    
    public function index()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        // Get ALL tasks (not just user's tasks) - for supervisors/admin view
        $tasks = $this->taskModel->getTasksWithDetails();
        $projects = $this->projectModel->getUserProjects($userId);

        // Ensure each task has a status_code for frontend filtering
        foreach ($tasks as &$task) {
            if (!isset($task['status_code']) && isset($task['status_name'])) {
                $task['status_code'] = strtolower(str_replace(' ', '_', $task['status_name']));
            }
        }
        unset($task);

        $data = [
            'title' => 'All Tasks',
            'tasks' => $tasks,
            'projects' => $projects,
            'is_all_tasks' => true
        ];

        return $this->template->member('tasks/index', $data);
    }
    
    public function kanbanSelect()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;

        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $projects = $this->projectModel->getUserProjects($userId);
        
        $data = [
            'title' => 'Select Project - Kanban Board',
            'projects' => $projects,
            'breadcrumbs' => [
                ['title' => 'Tasks', 'url' => base_url('tasks')],
                ['title' => 'Kanban Board']
            ]
        ];

        
        return $this->template->member('tasks/kanban_select', $data);
    }
    
    public function kanban($projectId)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $project = $this->projectModel->getProjectById($projectId);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        // Check if user has access to this project
        if (!$this->projectModel->userHasAccess($userId, $projectId)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $allTasks = $this->taskModel->getKanbanTasks($projectId);

        // Get members from ProjectModel
        $members = $this->projectModel->getProjectMembers($projectId);

        // Organize tasks by status (use keys matching the kanban view: pending, in_progress, review, completed)
        $tasks = [
            'pending' => [],
            'in_progress' => [],
            'review' => [],
            'completed' => []
        ];
        foreach ($allTasks as $task) {
            $statusCode = $task['status_code'] ?? 'pending';
            if (isset($tasks[$statusCode])) {
                $tasks[$statusCode][] = $task;
            } else {
                $tasks['pending'][] = $task; // Default to pending if status not recognized
            }
        }

        $data = [
            'title' => 'Kanban Board - ' . $project['name'],
            'project' => $project,
            'tasks' => $tasks,
            'members' => $members
        ];

        return $this->template->member('tasks/kanban', $data);
    }
    
    public function create()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        if ($this->request->getMethod() === 'POST') {
            // Validate and sanitize input
            $projectId = $this->request->getPost('project_id');
            $title = trim($this->request->getPost('title'));
            $description = trim($this->request->getPost('description'));
            $dueDate = $this->request->getPost('due_date') ?: null;
            $estimatedHours = $this->request->getPost('estimated_hours') ?: null;
            $statusCode = $this->request->getPost('status') ?: 'pending';
            $priorityCode = $this->request->getPost('priority') ?: 'medium';
            $assignedTo = $this->request->getPost('assigned_to') ?: $userId;

            $statusLookup = $this->statusLookupModel->getStatusByTypeAndCode('task', $statusCode);
            $priorityLookup = $this->priorityLookupModel->getPriorityByTypeAndCode('task', $priorityCode);
            $statusId = $statusLookup['id'] ?? null;
            $priorityId = $priorityLookup['id'] ?? null;

            $taskData = [
                'project_id' => $projectId,
                'title' => $title ?: 'Untitled Task',
                'description' => $description,
                'due_date' => $dueDate,
                'estimated_hours' => $estimatedHours,
                'progress' => 0,
                'order_index' => 0
            ];

            if ($taskId = $this->taskModel->createTask($taskData, $statusId, $priorityId, $assignedTo, $userId)) {
                $this->projectModel->updateProgress($projectId);
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'task_created',
                    'table_name' => 'tasks',
                    'record_id' => $taskId,
                    'new_values' => json_encode($taskData)
                ]);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'task_id' => $taskId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create task'
                ]);
            }
        }
        
        // Get projects for dropdown
        $projects = $this->projectModel->getUserProjects($userId);
        $users = $this->userModel->getAllUsers();
        
        return view('tasks/create', [
            'projects' => $projects,
            'users' => $users
        ]);
    }
    
    public function edit($id)
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $task = $this->taskModel->getTaskById($id);
        if (!$task) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        if ($this->request->getMethod() === 'POST') {
            $oldData = $task;
            $title = trim($this->request->getPost('title'));
            $description = trim($this->request->getPost('description'));
            $dueDate = $this->request->getPost('due_date') ?: null;
            $estimatedHours = $this->request->getPost('estimated_hours') ?: null;
            $actualHours = $this->request->getPost('actual_hours') ?: null;
            $progress = $this->request->getPost('progress') ?: 0;
            $statusCode = $this->request->getPost('status');
            $priorityCode = $this->request->getPost('priority');
            $assignedTo = $this->request->getPost('assigned_to');

            $taskData = [
                'title' => $title ?: 'Untitled Task',
                'description' => $description,
                'due_date' => $dueDate,
                'estimated_hours' => $estimatedHours,
                'actual_hours' => $actualHours,
                'progress' => $progress
            ];

            $ok = $this->taskModel->updateTask($id, $taskData);
            // Update status if provided
            if ($statusCode) {
                $statusLookup = $this->statusLookupModel->getStatusByTypeAndCode('task', $statusCode);
                if ($statusLookup) {
                    $this->taskModel->setTaskStatus($id, $statusLookup['id'], $userId);
                }
            }
            // Update priority if provided
            if ($priorityCode) {
                $priorityLookup = $this->priorityLookupModel->getPriorityByTypeAndCode('task', $priorityCode);
                if ($priorityLookup) {
                    $this->taskModel->setTaskPriority($id, $priorityLookup['id'], $userId);
                }
            }
            // Update assignment if provided
            if ($assignedTo) {
                $this->taskModel->setTaskOwnership($id, $assignedTo, $userId);
            }
            // Update project progress
            $this->projectModel->updateProgress($task['project_id']);
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'task_updated',
                'table_name' => 'tasks',
                'record_id' => $id,
                'old_values' => json_encode($oldData),
                'new_values' => json_encode($taskData)
            ]);
            if ($ok) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update task'
                ]);
            }
        }
        
        $users = $this->userModel->getProjectMembers($task['project_id']);
        
        return $this->template->member('tasks/edit', [
            'task' => $task,
            'users' => $users
        ]);
    }
    
    public function view($id)
    {
        $task = $this->taskModel->getTaskById($id);
        
        if (!$task) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $comments = $this->taskModel->getTaskComments($id);
        
        return $this->template->member('tasks/view', [
            'task' => $task,
            'comments' => $comments
        ]);
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

        $task = $this->taskModel->getTaskById($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
        
        if ($this->taskModel->deleteTask($id, $userId)) {
            // Update project progress
            $this->projectModel->updateProgress($task['project_id']);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'task_deleted',
                'table_name' => 'tasks',
                'record_id' => $id,
                'old_values' => json_encode($task)
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete task'
            ]);
        }
    }
    
    public function updateStatus()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        $taskId = $this->request->getPost('task_id');
        $newStatusCode = $this->request->getPost('status');
        $newPosition = $this->request->getPost('position');
        $order = $this->request->getPost('order'); // array of task IDs in new order

        $task = $this->taskModel->getTaskById($taskId);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }

        $statusLookup = $this->statusLookupModel->getStatusByTypeAndCode('task', $newStatusCode);
        if (!$statusLookup) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status'
            ]);
        }

        $oldStatus = $task['status_name'] ?? 'Unknown';
        $ok = $this->taskModel->setTaskStatus($taskId, $statusLookup['id'], $userId);
        if ($ok) {
            // Update order_index for all tasks in this column if order is provided
            if (is_array($order)) {
                foreach ($order as $idx => $tid) {
                    $this->taskModel->updateTask($tid, ['order_index' => $idx]);
                }
            } elseif ($newPosition !== null) {
                $this->taskModel->updateTask($taskId, ['order_index' => $newPosition]);
            }
            // Always update the main tasks table's date_modified field after status change
            $this->taskModel->updateTask($taskId, ['date_modified' => date('Y-m-d H:i:s')]);
            $this->projectModel->updateProgress($task['project_id']);
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'task_status_changed',
                'table_name' => 'tasks',
                'record_id' => $taskId,
                'old_values' => json_encode(['status' => $oldStatus]),
                'new_values' => json_encode(['status' => $statusLookup['name']])
            ]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task status updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update task status'
            ]);
        }
    }
    
    public function addComment()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        $taskId = $this->request->getPost('task_id');
        $comment = $this->request->getPost('comment');
        
        if (empty($comment)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Comment cannot be empty'
            ]);
        }
        
        if ($this->taskModel->addTaskComment($taskId, $userId, $comment)) {
            $task = $this->taskModel->getTaskById($taskId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'comment_added',
                'table_name' => 'task_comments',
                'record_id' => $taskId,
                'new_values' => json_encode(['comment' => $comment])
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comment added successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add comment'
            ]);
        }
    }
    
    public function myTasks()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        $tasks = $this->taskModel->getUserTasks($userId);
        $projects = $this->projectModel->getUserProjects($userId);

        // Group tasks by status for dashboard counts
        $grouped_tasks = [
            'pending' => [],
            'in_progress' => [],
            'review' => [],
            'completed' => []
        ];
        foreach ($tasks as $task) {
            $status = $task['status_code'] ?? strtolower(str_replace(' ', '_', $task['status_name'] ?? 'pending'));
            if (isset($grouped_tasks[$status])) {
                $grouped_tasks[$status][] = $task;
            } else {
                $grouped_tasks['pending'][] = $task;
            }
        }

        // Fetch status and priority options from lookup tables
        $statusOptions = $this->statusLookupModel->getStatusesByType('task');
        $priorityOptions = $this->priorityLookupModel->getPrioritiesByType('task');

        $data = [
            'title' => 'My Tasks',
            'tasks' => $tasks,
            'projects' => $projects,
            'is_my_tasks' => true,
            'status_options' => $statusOptions,
            'priority_options' => $priorityOptions,
            'grouped_tasks' => $grouped_tasks,
            'pending_count' => count($grouped_tasks['pending']),
            'in_progress_count' => count($grouped_tasks['in_progress']),
            'review_count' => count($grouped_tasks['review']),
            'completed_count' => count($grouped_tasks['completed']),
            'breadcrumbs' => [
                ['title' => 'Tasks', 'url' => base_url('tasks')],
                ['title' => 'My Tasks']
            ]
        ];

        return $this->template->member('tasks/my_tasks', $data);
    }

    // AJAX endpoint for tasks data (for DataTables/grid)
    public function getTasks()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }

        // Get filters from query params
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $projectId = $this->request->getGet('project_id');
        $priority = $this->request->getGet('priority');
        $assignedTo = $this->request->getGet('assigned_to');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = 12;

        $tasks = $this->taskModel->getTasksWithDetails($projectId);

        // Filter by status
        if ($status && $status !== 'all') {
            $tasks = array_filter($tasks, function($t) use ($status) {
                return (isset($t['status_code']) && $t['status_code'] === $status);
            });
        }
        // Filter by priority
        if ($priority && $priority !== 'all') {
            $tasks = array_filter($tasks, function($t) use ($priority) {
                return (isset($t['priority_name']) && strtolower($t['priority_name']) === strtolower($priority));
            });
        }
        // Filter by assignee
        if ($assignedTo && $assignedTo !== 'all') {
            $tasks = array_filter($tasks, function($t) use ($assignedTo) {
                return (isset($t['owner_first_name']) && $t['owner_first_name'] && isset($t['owner_last_name']) && $t['owner_last_name'] && isset($t['owner_id']) && $t['owner_id'] == $assignedTo);
            });
        }
        // Filter by search
        if ($search) {
            $search = strtolower($search);
            $tasks = array_filter($tasks, function($t) use ($search) {
                return (strpos(strtolower($t['title']), $search) !== false) || (isset($t['description']) && strpos(strtolower($t['description']), $search) !== false);
            });
        }

        $tasks = array_values($tasks);
        $total = count($tasks);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $tasksPage = array_slice($tasks, $offset, $perPage);

        // Map to frontend structure
        $tasksPage = array_map(function($t) {
            return [
                'id' => $t['id'],
                'title' => $t['title'],
                'description' => $t['description'],
                'project_name' => $t['project_name'],
                'status' => $t['status_code'] ?? 'pending',
                'priority' => strtolower($t['priority_name'] ?? 'medium'),
                'assigned_first_name' => $t['owner_first_name'] ?? '',
                'assigned_last_name' => $t['owner_last_name'] ?? '',
                'due_date' => $t['due_date'],
            ];
        }, $tasksPage);

        return $this->response->setJSON([
            'success' => true,
            'tasks' => $tasksPage,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total' => $total
            ]
        ]);
    }

    // AJAX endpoint for filter options (projects, users)
    public function getFilterOptions()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }
        $projects = $this->projectModel->getUserProjects($userId);
        $users = $this->userModel->getAllUsers();
        $projectOptions = array_map(function($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name']
            ];
        }, $projects);
        $userOptions = array_map(function($u) {
            return [
                'id' => $u['id'],
                'first_name' => $u['first_name'],
                'last_name' => $u['last_name']
            ];
        }, $users);
        return $this->response->setJSON([
            'success' => true,
            'projects' => $projectOptions,
            'users' => $userOptions
        ]);
    }
}
