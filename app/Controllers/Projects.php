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
        $stats = $this->projectModel->getProjectStats($id);
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
