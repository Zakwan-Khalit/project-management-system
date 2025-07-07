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
    
    public function __construct()
    {
        $this->taskModel = new TaskModel();
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
    }
    
    public function index()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        $tasks = $this->taskModel->getUserTasks($userId);
        $projects = $this->projectModel->getUserProjects($userId);
        
        $data = [
            'title' => 'Tasks',
            'tasks' => $tasks,
            'projects' => $projects,
            'breadcrumbs' => [
                ['title' => 'Tasks']
            ]
        ];
        
        return $this->template->member('tasks/index', $data);
    }
    
    // Simple test method to verify controller is accessible
    public function test()
    {
        echo "Tasks controller is working!";
        exit;
    }
    
    public function kanbanSelect()
    {
        // Debug: Log that this method is being called
        log_message('debug', 'Tasks::kanbanSelect() method called');
        
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        $projects = $this->projectModel->getUserProjects($userId);
        
        $data = [
            'title' => 'Select Project for Kanban Board',
            'projects' => $projects,
            'breadcrumbs' => [
                ['title' => 'Kanban Board']
            ]
        ];
        
        return $this->template->member('tasks/kanban_select', $data);
    }
    
    // Alias for auto-routing compatibility
    public function kanban_select()
    {
        return $this->kanbanSelect();
    }
    
    public function kanban($projectId)
    {
        $project = $this->projectModel->find($projectId);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $tasks = $this->taskModel->getKanbanTasks($projectId);
        $members = $this->userModel->getProjectMembers($projectId);
        
        $data = [
            'title' => 'Kanban Board - ' . $project['name'],
            'project' => $project,
            'tasks' => $tasks,
            'members' => $members,
            'breadcrumbs' => [
                ['title' => 'Kanban', 'url' => base_url('kanban')],
                ['title' => $project['name']]
            ]
        ];
        
        $this->template->member('tasks/kanban', $data);
    }
    
    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $userData = session('userdata');
            $userId = $userData['id'] ?? null;
            
            $data = [
                'project_id' => $this->request->getPost('project_id'),
                'assigned_to' => $this->request->getPost('assigned_to'),
                'created_by' => $userId,
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status') ?: 'pending',
                'priority' => $this->request->getPost('priority') ?: 'medium',
                'due_date' => $this->request->getPost('due_date'),
                'estimated_hours' => $this->request->getPost('estimated_hours'),
                'parent_task_id' => $this->request->getPost('parent_task_id'),
                'progress' => 0,
                'position' => 0
            ];
            
            if ($taskId = $this->taskModel->insert($data)) {
                // Update project progress
                $this->projectModel->updateProgress($data['project_id']);
                
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'project_id' => $data['project_id'],
                    'task_id' => $taskId,
                    'action' => 'task_created',
                    'description' => 'Created task: ' . $data['title']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'task_id' => $taskId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create task',
                    'errors' => $this->taskModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }
    
    public function edit($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
        
        if ($this->request->getMethod() === 'POST') {
            $userData = session('userdata');
            $userId = $userData['id'] ?? null;
            $oldData = $task;
            
            $data = [
                'assigned_to' => $this->request->getPost('assigned_to'),
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status'),
                'priority' => $this->request->getPost('priority'),
                'due_date' => $this->request->getPost('due_date'),
                'estimated_hours' => $this->request->getPost('estimated_hours'),
                'actual_hours' => $this->request->getPost('actual_hours'),
                'progress' => $this->request->getPost('progress')
            ];
            
            if ($this->taskModel->update($id, $data)) {
                // Update project progress
                $this->projectModel->updateProgress($task['project_id']);
                
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => $userId,
                    'project_id' => $task['project_id'],
                    'task_id' => $id,
                    'action' => 'task_updated',
                    'description' => 'Updated task: ' . $data['title'],
                    'old_values' => $oldData,
                    'new_values' => $data
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update task',
                    'errors' => $this->taskModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }
    
    public function view($id)
    {
        $tasks = $this->taskModel->getTasksWithDetails();
        $task = null;
        
        foreach ($tasks as $t) {
            if ($t['id'] == $id) {
                $task = $t;
                break;
            }
        }
        
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
        
        $comments = $this->taskModel->getTaskComments($id);
        $subTasks = $this->taskModel->getSubTasks($id);
        
        return $this->response->setJSON([
            'success' => true,
            'task' => $task,
            'comments' => $comments,
            'subTasks' => $subTasks
        ]);
    }
    
    public function delete($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
        
        if ($this->taskModel->delete($id)) {
            $userData = session('userdata');
            $userId = $userData['id'] ?? null;
            
            // Update project progress
            $this->projectModel->updateProgress($task['project_id']);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'project_id' => $task['project_id'],
                'task_id' => $id,
                'action' => 'task_deleted',
                'description' => 'Deleted task: ' . $task['title']
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
        $taskId = $this->request->getPost('task_id');
        $newStatus = $this->request->getPost('status');
        $newPosition = $this->request->getPost('position');
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
        
        $oldStatus = $task['status'];
        
        if ($this->taskModel->updateTaskPosition($taskId, $newStatus, $newPosition)) {
            // Update project progress
            $this->projectModel->updateProgress($task['project_id']);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'project_id' => $task['project_id'],
                'task_id' => $taskId,
                'action' => 'task_status_changed',
                'description' => 'Changed task status from ' . $oldStatus . ' to ' . $newStatus,
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => $newStatus]
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
        $taskId = $this->request->getPost('task_id');
        $comment = $this->request->getPost('comment');
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        $db = \Config\Database::connect();
        
        $data = [
            'task_id' => $taskId,
            'user_id' => $userId,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($db->table('task_comments')->insert($data)) {
            $task = $this->taskModel->find($taskId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => $userId,
                'project_id' => $task['project_id'],
                'task_id' => $taskId,
                'action' => 'comment_added',
                'description' => 'Added comment to task: ' . $task['title']
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
    
    // AJAX Methods for the Tasks Index Page
    public function getTasks()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $status = $this->request->getGet('status');
        $priority = $this->request->getGet('priority');
        $project_id = $this->request->getGet('project_id');
        $assigned_to = $this->request->getGet('assigned_to');
        $search = $this->request->getGet('search');

        // Start with base query for user's tasks
        $builder = $this->taskModel->select('
            tasks.*, 
            projects.name as project_name,
            assigned_user.first_name as assigned_first_name,
            assigned_user.last_name as assigned_last_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('projects', 'projects.id = tasks.project_id')
        ->join('users as assigned_user', 'assigned_user.id = tasks.assigned_to', 'left')
        ->join('users as creator', 'creator.id = tasks.created_by')
        ->where('tasks.is_delete', 0);

        // Apply filters
        if ($status && $status !== 'all') {
            $builder->where('tasks.status', $status);
        }
        
        if ($priority && $priority !== 'all') {
            $builder->where('tasks.priority', $priority);
        }
        
        if ($project_id && $project_id !== 'all') {
            $builder->where('tasks.project_id', $project_id);
        }
        
        if ($assigned_to && $assigned_to !== 'all') {
            $builder->where('tasks.assigned_to', $assigned_to);
        }
        
        if ($search) {
            $builder->groupStart()
                   ->like('tasks.title', $search)
                   ->orLike('tasks.description', $search)
                   ->groupEnd();
        }

        // Get tasks that the user is involved in (assigned to or created by)
        $builder->groupStart()
               ->where('tasks.assigned_to', $userId)
               ->orWhere('tasks.created_by', $userId)
               ->groupEnd();

        $tasks = $builder->orderBy('tasks.position', 'ASC')
                        ->orderBy('tasks.created_at', 'DESC')
                        ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    public function getTaskStats()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get tasks that the user is involved in
        $builder = $this->taskModel->where('is_delete', 0)
                                  ->groupStart()
                                  ->where('assigned_to', $userId)
                                  ->orWhere('created_by', $userId)
                                  ->groupEnd();

        $totalTasks = $builder->countAllResults(false);
        $pendingTasks = $builder->where('status', 'pending')->countAllResults(false);
        $inProgressTasks = $builder->where('status', 'in_progress')->countAllResults(false);
        $completedTasks = $builder->where('status', 'completed')->countAllResults(false);
        $overdueTasks = $builder->where('due_date <', date('Y-m-d'))
                               ->where('status !=', 'completed')
                               ->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'total' => $totalTasks,
                'pending' => $pendingTasks,
                'in_progress' => $inProgressTasks,
                'completed' => $completedTasks,
                'overdue' => $overdueTasks
            ]
        ]);
    }

    public function getFilterOptions()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get projects the user has access to
        $projects = $this->projectModel->getUserProjects($userId);
        
        // Get users who are assigned to tasks in projects the user has access to
        $projectIds = array_column($projects, 'id');
        
        $users = [];
        if (!empty($projectIds)) {
            $users = $this->userModel->select('users.id, users.first_name, users.last_name')
                                    ->join('tasks', 'tasks.assigned_to = users.id')
                                    ->whereIn('tasks.project_id', $projectIds)
                                    ->where('tasks.is_delete', 0)
                                    ->groupBy('users.id')
                                    ->findAll();
        }

        return $this->response->setJSON([
            'success' => true,
            'projects' => $projects,
            'users' => $users
        ]);
    }

    public function testTasksAjax()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Tasks AJAX is working',
            'data' => [
                'controller' => 'Tasks',
                'method' => 'testTasksAjax',
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
