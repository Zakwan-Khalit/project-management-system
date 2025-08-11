<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get project progress data for a specific project
     */
    public function getProjectProgressData($projectId)
    {
        // Get all active headers for this project
        $headerRows = $this->db->table('task_headers')
            ->select('id, column_name')
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->get()
            ->getResultArray();

        // Build a map: id => column_name
        $headerMap = [];
        foreach ($headerRows as $row) {
            $headerMap[(string)$row['id']] = $row['column_name'];
        }

        // Identify which header IDs are for planned/actual start/end
        $plannedStartIds = [];
        $plannedEndIds = [];
        $actualStartIds = [];
        $actualEndIds = [];
        foreach ($headerMap as $id => $name) {
            if (stripos($name, 'planned start') !== false || stripos($name, 'start date') !== false) {
                $plannedStartIds[] = $id;
            }
            if (stripos($name, 'planned end') !== false || stripos($name, 'end date') !== false) {
                $plannedEndIds[] = $id;
            }
            if (stripos($name, 'actual start') !== false) {
                $actualStartIds[] = $id;
            }
            if (stripos($name, 'actual end') !== false) {
                $actualEndIds[] = $id;
            }
        }

        // Get project scopes with their templates
        $scopesQuery = $this->db->table('project_scopes ps')
            ->select('ps.id as scope_id, ps.name as scope_name, ps.scope_order')
            ->where('ps.project_id', $projectId)
            ->where('ps.is_delete', 0)
            ->orderBy('ps.scope_order', 'ASC')
            ->get()
            ->getResultArray();

        $progressData = [];
        $scopeNum = 1; // Start with 1 and increment

        foreach ($scopesQuery as $scope) {
            // Add scope header row (X.0)
            $progressData[] = [
                'type' => 'scope',
                'num' => $scopeNum . '.0',
                'activity' => $scope['scope_name'],
                'planned_start' => '',
                'planned_end' => '',
                'actual_start' => '',
                'actual_end' => '',
                'planned_percentage' => '',
                'actual_percentage' => '',
                'variant' => '',
                'status' => '',
                'status_color' => ''
            ];

            // Get templates for this scope
            $templates = $this->db->table('task_templates tt')
                ->select('tt.id as template_id, tt.name as template_name, tt.weightage, tt.component_order')
                ->where('tt.scope_id', $scope['scope_id'])
                ->where('tt.is_delete', 0)
                ->orderBy('tt.component_order', 'ASC')
                ->get()
                ->getResultArray();

            if (empty($templates)) {
                // Add a note that this scope has no components
                $progressData[] = [
                    'type' => 'component',
                    'num' => $scopeNum . '.1',
                    'activity' => 'No components defined',
                    'planned_start' => '',
                    'planned_end' => '',
                    'actual_start' => '',
                    'actual_end' => '',
                    'planned_percentage' => '0.00',
                    'actual_percentage' => '0.00',
                    'variant' => '0.00',
                    'status' => 'Pending Setup',
                    'status_color' => '#6b7280'
                ];
                continue;
            }

            foreach ($templates as $template) {
                $componentNum = $template['component_order'];
                
                // Get tasks for this template
                $tasks = $this->getTasksForTemplate($template['template_id'], $projectId);
                
                $activityNumber = $scopeNum . '.' . $componentNum;
                
                if (empty($tasks)) {
                    // Add template without tasks
                    $progressData[] = [
                        'type' => 'component',
                        'num' => $activityNumber,
                        'activity' => $template['template_name'],
                        'planned_start' => '',
                        'planned_end' => '',
                        'actual_start' => '',
                        'actual_end' => '',
                        'planned_percentage' => number_format($template['weightage'] ?? 0, 2),
                        'actual_percentage' => '0.00',
                        'variant' => '0.00',
                        'status' => 'Pending Execution',
                        'status_color' => '#374151'
                    ];
                    continue;
                }

                // Calculate dates and progress from tasks
                $plannedStart = null;
                $plannedEnd = null;
                $actualStart = null;
                $actualEnd = null;
                $plannedStartDates = [];
                $plannedEndDates = [];
                $actualStartDates = [];
                $actualEndDates = [];
                $totalProgress = 0;
                $taskCount = count($tasks);

                foreach ($tasks as $task) {
                    $taskData = json_decode($task['data'], true) ?? [];

                    // Helper to validate date string (dd/mm/yyyy or yyyy-mm-dd)
                    $isValidDate = function($date) {
                        if (!$date || !is_string($date)) return false;
                        // Accept yyyy-mm-dd
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return true;
                        // Accept dd/mm/yyyy
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) return true;
                        return false;
                    };

                    // Extract planned/actual dates using joined header IDs
                    $plannedStartDate = $this->extractDateFromTask($taskData, $plannedStartIds);
                    $plannedEndDate = $this->extractDateFromTask($taskData, $plannedEndIds);
                    $actualStartDate = $this->extractDateFromTask($taskData, $actualStartIds);
                    $actualEndDate = $this->extractDateFromTask($taskData, $actualEndIds);

                    if ($isValidDate($plannedStartDate)) $plannedStartDates[] = $plannedStartDate;
                    if ($isValidDate($plannedEndDate)) $plannedEndDates[] = $plannedEndDate;
                    if ($isValidDate($actualStartDate)) $actualStartDates[] = $actualStartDate;
                    if ($isValidDate($actualEndDate)) $actualEndDates[] = $actualEndDate;

                    // Extract progress (field ID 9=Progress %)
                    $progress = $this->extractProgressFromTask($taskData, ['9', 'progress', 'Progress']);
                    $totalProgress += $progress;
                }

                // Show date if at least one task has a non-empty value for that date
                if (!empty($plannedStartDates)) {
                    $plannedStart = min(array_map(function($d) use ($isValidDate) {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $d)) {
                            $parts = explode('/', $d);
                            return strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
                        }
                        return strtotime($d);
                    }, $plannedStartDates));
                }
                if (!empty($plannedEndDates)) {
                    $plannedEnd = max(array_map(function($d) use ($isValidDate) {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $d)) {
                            $parts = explode('/', $d);
                            return strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
                        }
                        return strtotime($d);
                    }, $plannedEndDates));
                }
                if (!empty($actualStartDates)) {
                    $actualStart = min(array_map(function($d) use ($isValidDate) {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $d)) {
                            $parts = explode('/', $d);
                            return strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
                        }
                        return strtotime($d);
                    }, $actualStartDates));
                }
                if (!empty($actualEndDates)) {
                    $actualEnd = max(array_map(function($d) use ($isValidDate) {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $d)) {
                            $parts = explode('/', $d);
                            return strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
                        }
                        return strtotime($d);
                    }, $actualEndDates));
                }

                $plannedPercentage = $template['weightage'] ?? 0;

                if ($taskCount > 0) {
                    $avgProgress = $totalProgress / $taskCount;
                    $actualPercentage = ($plannedPercentage * $avgProgress) / 100;
                    $variant = $actualPercentage - $plannedPercentage;
                    
                } else {
                    $avgProgress = 0;
                    $actualPercentage = 0;
                    $variant = 0 - $plannedPercentage;
                }

                // Determine status and color
                $status = $this->determineTaskStatus($avgProgress, $actualStart, $actualEnd);
                
                $progressData[] = [
                    'type' => 'component',
                    'num' => $activityNumber,
                    'activity' => $template['template_name'],
                    'planned_start' => $plannedStart ? date('d/m/Y', $plannedStart) : '',
                    'planned_end' => $plannedEnd ? date('d/m/Y', $plannedEnd) : '',
                    'actual_start' => $actualStart ? date('d/m/Y', $actualStart) : '',
                    'actual_end' => $actualEnd ? date('d/m/Y', $actualEnd) : '',
                    'planned_percentage' => number_format($plannedPercentage, 2),
                    'actual_percentage' => number_format($actualPercentage, 2),
                    'variant' => number_format($variant, 2),
                    'status' => $status['label'],
                    'status_color' => $status['color']
                ];
            }
            
            $scopeNum++; // Increment for next scope
        }

        return $progressData;
    }

    /**
     * Get completed projects data
     */
    public function getCompletedProjectsData()
    {
        // Get completed projects with their scopes
        $completedProjects = $this->db->table('projects p')
            ->select('p.id, p.name, p.code')
            ->join('project_status ps', 'ps.project_id = p.id AND ps.is_active = 1', 'inner')
            ->join('status_lookup sl', 'sl.id = ps.status_id AND sl.code = "completed"', 'inner')
            ->where('p.is_delete', 0)
            ->get()
            ->getResultArray();

        $completedData = [];

        foreach ($completedProjects as $project) {
            // Get scopes for this project
            $scopes = $this->db->table('project_scopes')
                ->where('project_id', $project['id'])
                ->where('is_delete', 0)
                ->orderBy('scope_order', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($scopes as $scope) {
                // Calculate completion percentages for this scope
                $totalPercentage = 100.00;
                $plannedPercentage = $this->calculateScopePlannedPercentage($scope['id']);
                $actualPercentage = $this->calculateScopeActualPercentage($scope['id'], $project['id']);
                $variant = $actualPercentage - $plannedPercentage;

                $completedData[] = [
                    'scope' => $scope['name'],
                    'scope_details' => $scope['description'] ?? $project['name'],
                    'total_percentage' => number_format($totalPercentage, 2),
                    'planned_percentage' => number_format($plannedPercentage, 2),
                    'actual_percentage' => number_format($actualPercentage, 2),
                    'variant' => number_format($variant, 2)
                ];
            }
        }

        return $completedData;
    }

    /**
     * Get project completion status data
     */
    public function getProjectCompletionStatusData($userId = null)
    {
        $builder = $this->db->table('projects p')
            ->select('p.id, p.name, p.start_date, p.end_date')
            ->where('p.is_delete', 0)
            ->where('p.is_active', 1);
        if ($userId) {
            $builder->join('project_members pm', 'pm.project_id = p.id', 'inner');
            $builder->where('pm.user_id', $userId);
            $builder->where('pm.is_delete', 0);
        }
        $projects = $builder->get()->getResultArray();

        $statusData = [];

        foreach ($projects as $project) {
            // Get current project status
            $status = $this->db->table('project_status ps')
                ->select('sl.name as status_name, sl.code as status_code')
                ->join('status_lookup sl', 'sl.id = ps.status_id', 'inner')
                ->where('ps.project_id', $project['id'])
                ->where('ps.is_active', 1)
                ->where('ps.is_delete', 0)
                ->get()
                ->getRowArray();

            $statusName = $status['status_name'] ?? 'Unknown';
            $statusCode = $status['status_code'] ?? '';
            
            // Calculate days early/late
            $daysEarly = '';
            $daysLate = '';
            
            if ($project['end_date'] && $statusCode === 'completed') {
                $endDate = new \DateTime($project['end_date']);
                $today = new \DateTime();
                $diff = $today->diff($endDate);
                
                if ($today < $endDate) {
                    $daysEarly = $diff->days;
                } elseif ($today > $endDate) {
                    $daysLate = $diff->days;
                }
            } elseif ($project['end_date'] && $statusCode !== 'completed') {
                $endDate = new \DateTime($project['end_date']);
                $today = new \DateTime();
                if ($today > $endDate) {
                    $daysLate = $today->diff($endDate)->days;
                }
            }

            $statusData[] = [
                'project_name' => $project['name'],
                'status' => $statusName,
                'days_early' => $daysEarly ? $daysEarly : '-',
                'days_late' => $daysLate ? $daysLate : '-'
            ];
        }

        return $statusData;
    }

    /**
     * Helper methods
     */
    private function getTasksForTemplate($templateId, $projectId)
    {
        return $this->db->table('tasks')
            ->where('template_id', $templateId)
            ->where('project_id', $projectId)
            ->where('is_delete', 0)
            ->orderBy('task_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function extractDateFromTask($taskData, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($taskData[$key]) && !empty($taskData[$key])) {
                return $taskData[$key];
            }
        }
        return null;
    }

    private function extractProgressFromTask($taskData, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($taskData[$key]) && !empty($taskData[$key])) {
                $value = $taskData[$key];
                
                // Handle percentage strings like "60%" or numeric values like 60
                if (is_numeric($value)) {
                    return (float)$value;
                } elseif (is_string($value) && str_ends_with($value, '%')) {
                    // Remove the % sign and convert to float
                    $numericValue = str_replace('%', '', $value);
                    if (is_numeric($numericValue)) {
                        return (float)$numericValue;
                    }
                }
            }
        }
        return 0;
    }

    private function determineTaskStatus($progress, $actualStart, $actualEnd)
    {
        if ($progress >= 100) {
            return ['label' => 'Finished Ahead of Schedule (>0%)', 'color' => 'green'];
        } elseif ($progress >= 80) {
            return ['label' => 'Follow Schedule', 'color' => 'yellow'];
        } elseif ($progress >= 50) {
            return ['label' => 'Finished Late', 'color' => 'purple'];
        } elseif ($progress > 0) {
            return ['label' => 'Behind Schedule (0% < -10%)', 'color' => 'red'];
        } else {
            return ['label' => 'Pending Execution', 'color' => 'black'];
        }
    }

    private function calculateScopePlannedPercentage($scopeId)
    {
        // Get all templates for this scope and sum their weightage
        $result = $this->db->table('task_templates')
            ->selectSum('weightage')
            ->where('scope_id', $scopeId)
            ->where('is_delete', 0)
            ->get()
            ->getRowArray();

        return $result['weightage'] ?? 0;
    }

    private function calculateScopeActualPercentage($scopeId, $projectId)
    {
        // Get templates for this scope
        $templates = $this->db->table('task_templates')
            ->where('scope_id', $scopeId)
            ->where('is_delete', 0)
            ->get()
            ->getResultArray();

        $totalActual = 0;

        foreach ($templates as $template) {
            $tasks = $this->getTasksForTemplate($template['id'], $projectId);
            $templateProgress = 0;
            $taskCount = count($tasks);

            if ($taskCount > 0) {
                foreach ($tasks as $task) {
                    $taskData = json_decode($task['data'], true) ?? [];
                    $progress = $this->extractProgressFromTask($taskData, ['9', 'progress', 'Progress']);
                    $templateProgress += $progress;
                }
                $avgProgress = $templateProgress / $taskCount;
                $templateActual = ($template['weightage'] * $avgProgress) / 100;
                $totalActual += $templateActual;
            }
        }

        return $totalActual;
    }
}
