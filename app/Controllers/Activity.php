<?php
namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\TaskModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use App\Models\ProjectModel;

class Activity extends BaseController
{
    protected $activityModel;
    protected $taskModel;
    protected $userModel;
    protected $activityLog;
    protected $projectModel;
    protected $db;
    
    public function __construct()
    {
        $this->activityModel = new ActivityModel();
        $this->taskModel = new TaskModel();
        $this->userModel = new UserModel();
        $this->activityLog = new ActivityLogModel();
        $this->projectModel = new ProjectModel();
        $this->db = \Config\Database::connect();
    }

    // Activity list page (previously project_list)
    public function index()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        $projects = $this->projectModel->getUserProjects($userId);
        
        // Get status options from project model
        $status_options = $this->projectModel->getStatusOptions();
        $status_colors = $this->projectModel->getStatusColors();
        
        $data = [
            'title' => 'Activities',
            'projects' => $projects,
            'status_options' => $status_options,
            'status_colors' => $status_colors,
            'breadcrumbs' => [
                ['title' => 'Activities']
            ]
        ];
        
        return $this->template->member('activity/activity', $data);
    }

    // Activity scope page (previously project_task)
    public function activity_scope($id)
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

        $scopes = $this->activityModel->getProjectScopes($id);
        $data = [
            'project' => $project,
            'scopes' => $scopes
        ];
        return $this->template->member('activity/activity_scope', $data);
    }

    // Dynamic activity page (previously task_dynamic as task_page)
    public function activity_dynamic($templateParam)
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
        $template = $this->activityModel->getTaskTemplateById($templateId, $project_id);

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
        $header_lookup = $this->activityModel->getHeaderLookupOptions();

        // Fetch tasks for this template and project
        $tasks = $this->activityModel->getTasksByTemplateIdAndProject($templateId, $project_id);
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

        return $this->template->member('activity/activity_dynamic', [
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

    // Save task functionality
    public function save_task()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $id = $this->request->getPost('id');
        $template_id = $this->request->getPost('template_id');
        $project_id = $this->request->getPost('project_id');
        
        if (!$template_id || !$project_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template ID and Project ID are required'
            ]);
        }

        // Get all POST data except system fields
        $data = $this->request->getPost();
        unset($data['id'], $data['template_id'], $data['project_id']);
        
        // Prepare task data
        $taskData = [
            'template_id' => $template_id,
            'project_id' => $project_id,
            'data' => json_encode($data),
            'date_modified' => date('Y-m-d H:i:s')
        ];

        if ($id) {
            // Update existing task
            $taskData['id'] = $id;
            $result = $this->activityModel->updateTask($taskData);
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task updated successfully'
                ]);
            }
        } else {
            // Create new task - get next task order
            $nextOrder = $this->activityModel->getNextTaskOrder($template_id, $project_id);
            $taskData['date_created'] = date('Y-m-d H:i:s');
            $taskData['is_active'] = 1;
            $taskData['is_delete'] = 0;
            $taskData['task_order'] = $nextOrder;
            $newId = $this->activityModel->createTask($taskData);
            if ($newId) {
                return $this->response->setJSON([
                    'success' => true,
                    'task_id' => $newId,
                    'message' => 'Task created successfully'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to save task'
        ]);
    }

    // Delete task functionality
    public function delete_task()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $id = $this->request->getPost('id');
        if ($id && $this->activityModel->deleteTask($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete task'
        ]);
    }

    // Update task order functionality
    public function update_task_order()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $task_order = $this->request->getPost('task_order');
        $template_id = $this->request->getPost('template_id');
        $project_id = $this->request->getPost('project_id');

        if (is_array($task_order) && $this->activityModel->updateTaskOrder($task_order, $template_id, $project_id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task order updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update task order'
        ]);
    }

    // Update headers functionality
    public function updateHeaders()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $input = $this->request->getJSON(true);
        $fields = $input['fields'] ?? [];
        $template_id = $input['template_id'] ?? null;

        if ($template_id && $this->activityModel->updateTemplateHeaders($template_id, $fields)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Headers updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update headers'
        ]);
    }

    // Add task header functionality
    public function add_task_header()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $column_name = $this->request->getPost('column_name');
        $template_id = $this->request->getPost('template_id');
        $project_id = $this->request->getPost('project_id');

        if ($column_name && $template_id && $project_id) {
            $newId = $this->activityModel->addTaskHeader($column_name, $template_id, $project_id);
            if ($newId) {
                return $this->response->setJSON([
                    'success' => true,
                    'id' => $newId,
                    'message' => 'Header added successfully'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to add header'
        ]);
    }

    // Get project scopes functionality
    public function get_project_scopes()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $project_id = $this->request->getGet('project_id');
        if ($project_id) {
            $scopes = $this->activityModel->getProjectScopes($project_id);
            return $this->response->setJSON([
                'success' => true,
                'scopes' => $scopes
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project ID is required'
        ]);
    }
    
    // Get tasks by template functionality
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
        $tasks = $this->activityModel->getTasksByTemplateIdAndProject($template_code, $project_id); // $template_code is now template_id
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

    // Update template order functionality
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

        $result = $this->activityModel->updateTemplateOrder($templateOrder, $scopeId);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Template order updated successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update template order']);
        }
    }

    // Create scope functionality
    public function create_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'create_scope',
            'description' => 'Created a new scope',
            'details' => json_encode($this->request->getPost()),
        ]);

        $projectId = $this->request->getPost('project_id');
        $scopeLookupId = $this->request->getPost('scope_lookup_id');
        // Treat both 0 and '0' (string) as null
        if ($scopeLookupId === '0' || $scopeLookupId === 0 || empty($scopeLookupId)) {
            $scopeLookupId = null;
        }
        $customScopeName = $this->request->getPost('custom_scope_name');

        if (!$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID is required']);
        }
        // If custom, set to null
        if (empty($scopeLookupId) || $scopeLookupId == 0) {
            $scopeLookupId = null;
        }
        $scopeId = $this->activityModel->createScope($projectId, $scopeLookupId, $customScopeName);

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

    // Update scope functionality
    public function update_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'update_scope',
            'description' => 'Updated scope',
            'details' => json_encode($this->request->getPost()),
        ]);

        $scopeId = $this->request->getPost('scope_id');
        $name = $this->request->getPost('name');

        if (!$scopeId || !$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Scope ID and name are required']);
        }

        $result = $this->activityModel->updateScope($scopeId, $name);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Scope updated successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update scope']);
        }
    }

    // Delete scope functionality
    public function delete_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'delete_scope',
            'description' => 'Deleted scope',
            'details' => json_encode($this->request->getPost()),
        ]);

        $scopeId = $this->request->getPost('scope_id');

        if (!$scopeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Scope ID is required']);
        }

        $result = $this->activityModel->deleteScope($scopeId);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Scope deleted successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete scope']);
        }
    }

    // Update component name functionality
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

    // Soft delete component functionality
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

    // Add template to scope functionality
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
        $result = $this->activityModel->addTemplatesToScope($scopeId, $templateIds);
        
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

    // Add custom template to scope functionality
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
                $weightage = $component['weightage'] ?? 0;

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
                    'weightage' => $weightage,
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
            ]
        ];

        return $defaultFields;
    }

    // Create component/template
    public function create_component()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'create_component',
            'description' => 'Created a new component',
            'details' => json_encode($this->request->getPost()),
        ]);

        $projectId = $this->request->getPost('project_id');
        $scopeId = $this->request->getPost('scope_id');
        $name = $this->request->getPost('component_name');
        $description = $this->request->getPost('component_description');
        $weightage = $this->request->getPost('weightage') ?? 0.00;

        // Validate weightage format (decimal with up to 2 decimal places)
        if (!is_numeric($weightage) || $weightage < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid weightage value']);
        }
        
        $weightage = round(floatval($weightage), 2);

        if (!$projectId || !$scopeId || !$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID, Scope ID, and component name are required']);
        }

        $templateId = $this->activityModel->createTemplate($projectId, $scopeId, $name, $description, $weightage);
        
        if ($templateId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Component created successfully',
                'template_id' => $templateId
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create component']);
        }
    }

    // Update component weightage
    public function update_component_weightage()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'update_component_name',
            'description' => 'Updated component name',
            'details' => json_encode($this->request->getPost()),
        ]);

        $templateId = $this->request->getPost('template_id');
        $weightage = $this->request->getPost('weightage') ?? 0.00;

        // Validate weightage format
        if (!is_numeric($weightage) || $weightage < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid weightage value']);
        }
        
        $weightage = round(floatval($weightage), 2);

        if (!$templateId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Template ID is required']);
        }

        $success = $this->activityModel->updateTemplateWeightage($templateId, $weightage);
        
        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Component weightage updated successfully'
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update component weightage']);
        }
    }

    // Get project templates for table display
    public function get_project_templates()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->activityLog->logActivity([
            'user_id' => session('userdata')['id'] ?? null,
            'action' => 'soft_delete_component',
            'description' => 'Soft deleted component',
            'details' => json_encode($this->request->getPost()),
        ]);

        $projectId = $this->request->getGet('project_id');
        $scopeId = $this->request->getGet('scope_id');
        
        if (!$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID is required']);
        }

        $templates = $scopeId
            ? $this->activityModel->getScopeTemplates($scopeId)
            : $this->activityModel->getTaskTemplatesByProject($projectId);

        // Map output for frontend compatibility (use component_name if available, fallback to name)
        $templates = array_map(function($tpl) {
            return [
                'id' => $tpl['id'],
                'name' => isset($tpl['component_name']) && $tpl['component_name'] ? $tpl['component_name'] : $tpl['name'],
                'weightage' => $tpl['weightage'],
                'component_order' => $tpl['component_order'],
                'scope_id' => $tpl['scope_id'],
                'component_id' => $tpl['component_id'] ?? null
            ];
        }, $templates);

        return $this->response->setJSON([
            'success' => true,
            'templates' => $templates
        ]);
    }

    // Read-only preview page for the activity table (AJAX loads data)
    public function preview_table()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return redirect()->to('/auth')->with('error', 'Please log in to access this page');
        }

        $template_id = $this->request->getGet('template_id');
        $project_id = $this->request->getGet('project_id');
        if (!$template_id || !$project_id) {
            return redirect()->back()->with('error', 'Missing template or project ID');
        }
        // Only render the view, JS will load data
        return $this->template->member('activity/preview_table', [
            'template_id' => $template_id,
            'project_id' => $project_id
        ]);
    }

    // AJAX: Get preview table data (headers, tasks, images)
    public function get_preview_table_data()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        $template_id = $this->request->getGet('template_id');
        $project_id = $this->request->getGet('project_id');
        if (!$template_id || !$project_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing template or project ID']);
        }

        // Get template
        $template = $this->activityModel->getTaskTemplateById($template_id, $project_id);
        if (!$template) {
            return $this->response->setJSON(['success' => false, 'message' => 'Template not found']);
        }

        // Get fields and header mapping
        $fields = [];
        $headerMap = [];
        if (!empty($template['fields'])) {
            $fields = json_decode($template['fields'], true);
            $all_headers = $this->activityModel->getHeaderLookupOptions();
            foreach ($all_headers as $header) {
                $headerMap[$header['id']] = $header['column_name'];
            }
        }

        // Get tasks
        $tasks = $this->activityModel->getTasksByTemplateIdAndProject($template_id, $project_id);
        
        // Process task data and add images if needed
        foreach ($tasks as &$task) {
            $task['data'] = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
            $task['images'] = []; // Initialize images array
        }
        unset($task);

        // Check if there's an Image field and load images
        foreach ($fields as $fieldId) {
            if (isset($headerMap[$fieldId]) && $headerMap[$fieldId] === 'Image') {
                $taskIds = array_column($tasks, 'id');
                if (!empty($taskIds)) {
                    $imageRows = $this->db->table('task_images')
                        ->whereIn('task_id', $taskIds)
                        ->where('is_delete', 0)
                        ->get()
                        ->getResultArray();
                    
                    foreach ($imageRows as $img) {
                        foreach ($tasks as &$task) {
                            if ($task['id'] == $img['task_id']) {
                                $task['images'][] = $img;
                            }
                        }
                        unset($task);
                    }
                }
                break;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'fields' => $fields,
            'headerMap' => $headerMap,
            'tasks' => $tasks
        ]);
    }

    // AJAX: Get all components for a given scope (for Add Component dropdown)
    public function get_components_by_scope()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $scopeId = $this->request->getGet('scope_id');
        if (!$scopeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Scope ID is required']);
        }

        $scopeRow = $this->db->table('project_scopes')->where('id', $scopeId)->get()->getRowArray();
        // pr($scopeRow);
        $scopeLookupId = $scopeRow['scope_lookup_id'] ?? null;

        $components = $this->activityModel->getComponentsByScope($scopeLookupId);
        return $this->response->setJSON([
            'success' => true,
            'components' => $components
        ]);
    }

    // AJAX: Get available scopes from scope_lookup not already in project_scopes for this project
    public function get_available_scopes()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $projectId = $this->request->getGet('project_id');
        if (!$projectId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Project ID is required']);
        }

        $scopes = $this->activityModel->getAvailableScopesForProject($projectId);
        return $this->response->setJSON([
            'success' => true,
            'scopes' => $scopes
        ]);
    }
}
