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
            $priority = $this->request->getPost('priority') ?: 'medium';
            $status = $this->request->getPost('status') ?: 'todo';
            $assignedTo = $this->request->getPost('assigned_to') ?: $userId;

            $taskData = [
                'project_id' => $projectId,
                'data' => json_encode([
                    'title' => $title ?: 'Untitled Task',
                    'description' => $description,
                    'due_date' => $dueDate,
                    'estimated_hours' => $estimatedHours,
                    'priority' => $priority,
                    'status' => $status,
                    'assigned_to' => $assignedTo,
                    'progress' => 0
                ])
            ];

            $taskId = $this->taskModel->createTask($taskData);
            if ($taskId) {
                $this->projectModel->updateProgress($projectId);
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'action' => 'task_created',
                    'details' => json_encode([
                        'task_id' => $taskId,
                        'data' => $taskData
                    ])
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
        
        // (Removed unreachable code after return in POST handler)
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

            $dataArr = [
                'title' => $title ?: 'Untitled Task',
                'description' => $description,
                'due_date' => $dueDate,
                'estimated_hours' => $estimatedHours,
                'actual_hours' => $actualHours,
            ];
            // (Removed unreachable code after return in POST handler)
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
                'details' => json_encode([
                    'task_id' => $id,
                    'old' => $oldData,
                    'new' => $taskData
                ])
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
                'details' => json_encode([
                    'task_id' => $id,
                    'old' => $task
                ])
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
            // Always update the main tasks table's date_modified field after status change
            $this->taskModel->updateTask($taskId, ['date_modified' => date('Y-m-d H:i:s')]);
            $this->projectModel->updateProgress($task['project_id']);
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'task_status_changed',
                'details' => json_encode([
                    'task_id' => $taskId,
                    'old_status' => $oldStatus,
                    'new_status' => $statusLookup['name']
                ])
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
                'details' => json_encode([
                    'task_id' => $taskId,
                    'comment' => $comment
                ])
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
            $priority = $this->request->getPost('priority') ?: 'medium';
            $status = $this->request->getPost('status') ?: 'todo';
            $assignedTo = $this->request->getPost('assigned_to') ?: null;

            $dataArr = [
                'title' => $title ?: 'Untitled Task',
                'description' => $description,
                'due_date' => $dueDate,
                'estimated_hours' => $estimatedHours,
                'actual_hours' => $actualHours,
                'progress' => $progress,
                'priority' => $priority,
                'status' => $status,
                'assigned_to' => $assignedTo
            ];
            $taskData = [
                'data' => json_encode($dataArr)
            ];
            $ok = $this->taskModel->updateTask($id, $taskData);
            $this->projectModel->updateProgress($task['project_id']);
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'action' => 'task_updated',
                'details' => json_encode([
                    'task_id' => $id,
                    'old' => $oldData,
                    'new' => $taskData
                ])
            ]);
            if ($ok) {
                return $this->response->setJSON([
                    'message' => 'Task updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'message' => 'Failed to update task'
                ]);
            }
        }
        // ...existing code...
    }
}
