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
        
        $this->template->member('projects/index', $data);
    }
    
    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status') ?: 'planning',
                'priority' => $this->request->getPost('priority') ?: 'medium',
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'budget' => $this->request->getPost('budget'),
                'created_by' => user_id(),
                'progress' => 0
            ];
            
            if ($projectId = $this->projectModel->insert($data)) {
                // Add creator as project lead
                $db = \Config\Database::connect();
                $db->table('project_members')->insert([
                    'project_id' => $projectId,
                    'user_id' => user_id(),
                    'role' => 'lead',
                    'joined_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => user_id(),
                    'project_id' => $projectId,
                    'action' => 'project_created',
                    'description' => 'Created project: ' . $data['name']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project created successfully',
                    'project_id' => $projectId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create project',
                    'errors' => $this->projectModel->errors()
                ]);
            }
        }
        
        return $this->template->member('projects/create');
    }
    
    public function view($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $tasks = $this->taskModel->getKanbanTasks($id);
        $members = $this->userModel->getProjectMembers($id);
        $stats = $this->projectModel->getProjectStats($id);
        $activities = $this->activityLog->getProjectActivities($id, 10);
        
        $data = [
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
        
        return $this->template
            ->set('title', $project['name'] . ' - Project Management System')
            ->set('page_title', 'Project: ' . $project['name'])
            ->set($data)
            ->member('projects/view');
    }
    
    public function edit($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status'),
                'priority' => $this->request->getPost('priority'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'budget' => $this->request->getPost('budget')
            ];
            
            if ($this->projectModel->update($id, $data)) {
                // Log activity
                $this->activityLog->logActivity([
                    'user_id' => session('user_id'),
                    'project_id' => $id,
                    'action' => 'project_updated',
                    'description' => 'Updated project: ' . $data['name'],
                    'old_values' => $project,
                    'new_values' => $data
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update project',
                    'errors' => $this->projectModel->errors()
                ]);
            }
        }
        
        return $this->template->member('projects/edit', ['project' => $project]);
    }
    
    public function delete($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project not found'
            ]);
        }
        
        if ($this->projectModel->delete($id)) {
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => session('user_id'),
                'project_id' => $id,
                'action' => 'project_deleted',
                'description' => 'Deleted project: ' . $project['name']
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
        $userId = $this->request->getPost('user_id');
        $role = $this->request->getPost('role') ?: 'member';
        
        $db = \Config\Database::connect();
        
        // Check if user is already a member
        $existing = $db->table('project_members')
                      ->where('project_id', $projectId)
                      ->where('user_id', $userId)
                      ->get()
                      ->getRowArray();
        
        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User is already a member of this project'
            ]);
        }
        
        $data = [
            'project_id' => $projectId,
            'user_id' => $userId,
            'role' => $role,
            'joined_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($db->table('project_members')->insert($data)) {
            $user = $this->userModel->find($userId);
            $project = $this->projectModel->find($projectId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => session('user_id'),
                'project_id' => $projectId,
                'action' => 'member_added',
                'description' => 'Added ' . $user['first_name'] . ' ' . $user['last_name'] . ' to project'
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
    
    public function removeMember($projectId, $userId)
    {
        $db = \Config\Database::connect();
        
        if ($db->table('project_members')
              ->where('project_id', $projectId)
              ->where('user_id', $userId)
              ->delete()) {
            
            $user = $this->userModel->find($userId);
            
            // Log activity
            $this->activityLog->logActivity([
                'user_id' => session('user_id'),
                'project_id' => $projectId,
                'action' => 'member_removed',
                'description' => 'Removed ' . $user['first_name'] . ' ' . $user['last_name'] . ' from project'
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

        $status = $this->request->getGet('status');
        $priority = $this->request->getGet('priority');
        $search = $this->request->getGet('search');

        // Get projects with detailed information using simpler approach
        $builder = $this->projectModel->select('
            projects.*, 
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('users as creator', 'creator.id = projects.created_by')
        ->where('projects.is_delete', 0);

        // Apply filters
        if ($status && $status !== '') {
            $builder->where('projects.status', $status);
        }
        
        if ($priority && $priority !== '') {
            $builder->where('projects.priority', $priority);
        }
        
        if ($search) {
            $builder->groupStart()
                   ->like('projects.name', $search)
                   ->orLike('projects.description', $search)
                   ->groupEnd();
        }

        // Get projects that the user has access to (created by or member of)
        $builder->groupStart()
               ->where('projects.created_by', $userId);
        
        // Add OR condition for project members
        $memberProjectIds = $this->db->table('project_members')
                                   ->select('project_id')
                                   ->where('user_id', $userId)
                                   ->get()
                                   ->getResultArray();
        
        if (!empty($memberProjectIds)) {
            $projectIds = array_column($memberProjectIds, 'project_id');
            $builder->orWhereIn('projects.id', $projectIds);
        }
        
        $builder->groupEnd();

        $projects = $builder->orderBy('projects.created_at', 'DESC')
                           ->findAll();

        // Add task and member counts separately to avoid complex subqueries
        foreach ($projects as &$project) {
            // Get task counts
            $totalTasks = $this->db->table('tasks')
                                 ->where('project_id', $project['id'])
                                 ->where('is_delete', 0)
                                 ->countAllResults();
            
            $completedTasks = $this->db->table('tasks')
                                     ->where('project_id', $project['id'])
                                     ->where('status', 'completed')
                                     ->where('is_delete', 0)
                                     ->countAllResults();
            
            $memberCount = $this->db->table('project_members')
                                  ->where('project_id', $project['id'])
                                  ->countAllResults();
            
            $project['total_tasks'] = $totalTasks;
            $project['completed_tasks'] = $completedTasks;
            $project['member_count'] = $memberCount;
        }

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

        // Get projects that the user has access to
        $userProjects = $this->projectModel->getUserProjects($userId);
        
        $stats = [
            'total' => count($userProjects),
            'completed' => 0,
            'in_progress' => 0,
            'delayed' => 0
        ];

        foreach ($userProjects as $project) {
            switch ($project['status']) {
                case 'completed':
                    $stats['completed']++;
                    break;
                case 'active':
                    $stats['in_progress']++;
                    break;
            }
            
            // Check if project is delayed (past end date and not completed)
            if ($project['end_date'] && strtotime($project['end_date']) < time() && $project['status'] !== 'completed') {
                $stats['delayed']++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'debug' => [
                'user_id' => $userId,
                'projects_count' => count($userProjects)
            ]
        ]);
    }

    // Simple test method to verify controller is accessible
    public function test()
    {
        echo "Projects controller is working!";
        exit;
    }

    public function testAjax()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'AJAX is working',
            'data' => [
                'controller' => 'Projects',
                'method' => 'testAjax',
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
