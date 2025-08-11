<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Get task templates by project
    public function getTaskTemplatesByProject($projectId)
    {
        return $this->db->table('task_templates')
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->orderBy('date_created', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get task template by ID and project
    public function getTaskTemplateById($templateId, $projectId)
    {
        return $this->db->table('task_templates')
            ->where('id', $templateId)
            ->where('project_id', $projectId)
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();
    }

    // Get tasks by template ID and project
    public function getTasksByTemplateIdAndProject($templateId, $projectId)
    {
        return $this->db->table('tasks')
            ->where('template_id', $templateId)
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->orderBy('task_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get header lookup options
    public function getHeaderLookupOptions()
    {
        return $this->db->table('task_headers')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->orderBy('column_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Create new task
    public function createTask($data)
    {
        if ($this->db->table('tasks')->insert($data)) {
            return $this->db->insertID();
        }
        return false;
    }

    // Get next task order for a template and project
    public function getNextTaskOrder($templateId, $projectId)
    {
        $result = $this->db->table('tasks')
            ->selectMax('task_order')
            ->where('template_id', $templateId)
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();
            
        $maxOrder = $result['task_order'] ?? 0;
        return $maxOrder + 1;
    }

    // Update existing task
    public function updateTask($data)
    {
        return $this->db->table('tasks')
            ->where('id', $data['id'])
            ->update($data);
    }

    // Delete task (soft delete)
    public function deleteTask($id)
    {
        return $this->db->table('tasks')
            ->where('id', $id)
            ->update([
                'is_delete' => 1,
                'is_active' => 0,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
    }

    // Update task order
    public function updateTaskOrder($taskOrder, $templateId, $projectId)
    {
        foreach ($taskOrder as $index => $taskId) {
            $this->db->table('tasks')
                ->where('id', $taskId)
                ->where('template_id', $templateId)
                ->where('project_id', $projectId)
                ->update([
                    'task_order' => $index + 1,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);
        }
        return true;
    }

    // Update template headers
    public function updateTemplateHeaders($templateId, $fields)
    {
        return $this->db->table('task_templates')
            ->where('id', $templateId)
            ->update(['fields' => json_encode($fields)]);
    }

    // Add task header
    public function addTaskHeader($columnName, $templateId, $projectId)
    {
        // First check if header already exists
        $existing = $this->db->table('task_headers')
            ->where('column_name', $columnName)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();
            
        if ($existing) {
            return $existing['id'];
        }
        
        // Create new header (headers are global, not tied to specific templates/projects)
        $data = [
            'column_name' => $columnName,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('task_headers')->insert($data)) {
            return $this->db->insertID();
        }
        return false;
    }

    // Get project scopes with their templates
    public function getProjectScopes($projectId)
    {
        $builder = $this->db->table('project_scopes ps');
        $builder->select('ps.*, COUNT(tt.id) as template_count');
        $builder->join('task_templates tt', 'tt.scope_id = ps.id AND tt.is_delete = 0', 'left');
        $builder->where('ps.project_id', $projectId);
        $builder->where('ps.is_delete', 0);
        $builder->where('ps.is_active', 1);
        $builder->groupBy('ps.id');
        $builder->orderBy('ps.scope_order', 'ASC');
        $scopes = $builder->get()->getResultArray();
        
        // Get templates for each scope
        foreach ($scopes as &$scope) {
            $scope['templates'] = $this->getScopeTemplates($scope['id']);
        }
        
        return $scopes;
    }

    /**
     * Get templates for a specific scope
     */
    public function getScopeTemplates($scopeId)
    {
        $builder = $this->db->table('task_templates');
        $builder->where('scope_id', $scopeId);
        $builder->where('is_delete', 0);
        $builder->where('is_active', 1);
        $builder->orderBy('component_order', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Get all tasks for a project
    public function getProjectTasks($projectId)
    {
        return $this->db->table('tasks')
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->orderBy('task_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get task by ID
    public function getTaskById($id)
    {
        return $this->db->table('tasks')
            ->where('id', $id)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();
    }

    // Get task statistics for a project
    public function getTaskStatistics($projectId)
    {
        $result = $this->db->table('tasks')
            ->select('COUNT(*) as total_tasks')
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();

        return [
            'total_tasks' => $result['total_tasks'] ?? 0
        ];
    }

    // Search tasks
    public function searchTasks($projectId, $searchTerm)
    {
        return $this->db->table('tasks')
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->like('data', $searchTerm)
            ->orderBy('task_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get tasks by template and status
    public function getTasksByStatus($templateId, $projectId, $status)
    {
        return $this->db->table('tasks')
            ->where('template_id', $templateId)
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->like('data', '"Status":"' . $status . '"')
            ->orderBy('task_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Bulk update tasks
    public function bulkUpdateTasks($tasks)
    {
        $this->db->transStart();
        
        foreach ($tasks as $task) {
            $this->db->table('tasks')
                ->where('id', $task['id'])
                ->update($task);
        }
        
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    // Get template statistics
    public function getTemplateStatistics($templateId, $projectId)
    {
        $tasks = $this->getTasksByTemplateIdAndProject($templateId, $projectId);
        $stats = [
            'total_tasks' => count($tasks),
            'completed_tasks' => 0,
            'in_progress_tasks' => 0,
            'pending_tasks' => 0
        ];

        foreach ($tasks as $task) {
            $data = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true);
            $status = $data['Status'] ?? 'pending';
            
            switch (strtolower($status)) {
                case 'completed':
                case 'done':
                    $stats['completed_tasks']++;
                    break;
                case 'in_progress':
                case 'in progress':
                    $stats['in_progress_tasks']++;
                    break;
                default:
                    $stats['pending_tasks']++;
                    break;
            }
        }

        return $stats;
    }

    /**
     * Create a new scope
     */
    public function createScope($projectId, $scopeLookupId, $customScopeName = null)
    {
        // Get the next order number
        $builder = $this->db->table('project_scopes');
        $builder->selectMax('scope_order');
        $builder->where('project_id', $projectId);
        $builder->where('is_delete', 0);
        $result = $builder->get()->getRowArray();
        $nextOrder = ($result['scope_order'] ?? 0) + 1;

        // Get the name from scope_lookup, or use custom
        $name = '';
        if ($scopeLookupId) {
            $lookup = $this->db->table('scope_lookup')->where('id', $scopeLookupId)->get()->getRowArray();
            $name = $lookup ? $lookup['name'] : '';
        }
        if ($customScopeName) {
            $name = $customScopeName;
        }

        if (empty($scopeLookupId) || $scopeLookupId == 0) {
            $scopeLookupId = null;
        }
        $data = [
            'project_id' => $projectId,
            'name' => $name,
            'scope_order' => $nextOrder,
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ];
        if ($scopeLookupId !== null) {
            $data['scope_lookup_id'] = $scopeLookupId;
        }

        if ($this->db->table('project_scopes')->insert($data)) {
            return $this->db->insertID();
        }
        return false;
    }

    /**
     * Update a scope
     */
    public function updateScope($scopeId, $name, $description = null)
    {
        $data = [
            'name' => $name,
            'date_modified' => date('Y-m-d H:i:s')
        ];

        return $this->db->table('project_scopes')
            ->where('id', $scopeId)
            ->where('is_delete', 0)
            ->update($data);
    }

    /**
     * Delete a scope (soft delete)
     */
    public function deleteScope($scopeId)
    {
        // First, remove templates from this scope
        $this->db->table('task_templates')
            ->where('scope_id', $scopeId)
            ->update(['scope_id' => null, 'date_modified' => date('Y-m-d H:i:s')]);

        // Then soft delete the scope
        return $this->db->table('project_scopes')
            ->where('id', $scopeId)
            ->update([
                'is_delete' => 1,
                'is_active' => 0,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Update template order within a scope
     */
    public function updateTemplateOrder($templateOrder, $scopeId)
    {
        if (!is_array($templateOrder)) return false;
        
        foreach ($templateOrder as $row) {
            $templateId = $row['template_id'];
            $order = $row['order'];
            $this->db->table('task_templates')
                ->where('id', $templateId)
                ->where('scope_id', $scopeId)
                ->update([
                    'component_order' => $order,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);
        }
        return true;
    }

    /**
     * Create a new template
     */
    public function createTemplate($projectId, $scopeId, $name, $description, $weightage = 0.00)
    {
        // Get the next component order number
        $builder = $this->db->table('task_templates');
        $builder->selectMax('component_order');
        $builder->where('scope_id', $scopeId);
        $builder->where('is_delete', 0);
        $result = $builder->get()->getRowArray();
        $nextOrder = ($result['component_order'] ?? 0) + 1;

        $data = [
            'project_id' => $projectId,
            'scope_id' => $scopeId,
            'name' => $name,
            'weightage' => $weightage,
            'component_order' => $nextOrder,
            'fields' => json_encode([1,2,3,4,5,6,7,8,9,10,11,12,13]), // Default fields
            'is_active' => 1,
            'is_delete' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('task_templates')->insert($data)) {
            return $this->db->insertID();
        }
        return false;
    }

    /**
     * Update template weightage
     */
    public function updateTemplateWeightage($templateId, $weightage)
    {
        return $this->db->table('task_templates')
            ->where('id', $templateId)
            ->where('is_delete', 0)
            ->update([
                'weightage' => $weightage,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Get all templates for a project (for the table display)
     */
    public function getProjectTemplates($projectId)
    {
        return $this->db->table('task_templates tt')
            ->select('tt.*, ps.name as scope_name')
            ->join('project_scopes ps', 'ps.id = tt.scope_id', 'left')
            ->where('tt.project_id', $projectId)
            ->where('tt.is_active', 1)
            ->where('tt.is_delete', 0)
            ->orderBy('ps.scope_order', 'ASC')
            ->orderBy('tt.component_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get all components for a given scope (for Add Component dropdown)
    public function getComponentsByScope($scopeId)
    {
        return $this->db->table('component_lookup')
            ->select('id, name')
            ->where('scope_id', $scopeId)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get available scopes from scope_lookup not already in project_scopes for this project
     * Returns: [id, name, description]
     */
    public function getAvailableScopesForProject($projectId)
    {
        // Get all scope_lookup.id not in project_scopes.scope_lookup_id for this project
        $builder = $this->db->table('scope_lookup');
        $builder->select('scope_lookup.id, scope_lookup.name');
        $builder->whereNotIn('scope_lookup.id', function($sub) use ($projectId) {
            $sub->select('scope_lookup_id')
                ->from('project_scopes')
                ->where('project_id', $projectId)
                ->where('is_delete', 0);
        });
        return $builder->get()->getResultArray();
    }
}
