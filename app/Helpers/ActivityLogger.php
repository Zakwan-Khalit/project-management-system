<?php

namespace App\Helpers;

use App\Models\ActivityLogModel;

class ActivityLogger
{
    protected $activityLog;
    
    public function __construct()
    {
        $this->activityLog = new ActivityLogModel();
    }
    
    /**
     * Log project activity
     */
    public function logProjectActivity($projectId, $action, $description, $details = null, $userId = null)
    {
        if (!$userId) {
            $userData = session('userdata');
            $userId = $userData['id'] ?? null;
        }
        
        return $this->activityLog->logActivity([
            'user_id' => $userId,
            'project_id' => $projectId,
            'action' => $action,
            'description' => $description,
            'details' => $details
        ]);
    }
    
    /**
     * Log project creation
     */
    public function logProjectCreated($projectId, $projectName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'created',
            'Project created',
            "New project '{$projectName}' has been created",
            $userId
        );
    }
    
    /**
     * Log project update
     */
    public function logProjectUpdated($projectId, $projectName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'updated',
            'Project updated',
            "Project '{$projectName}' details have been modified",
            $userId
        );
    }
    
    /**
     * Log team member addition
     */
    public function logTeamMemberAdded($projectId, $memberName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'team_member_added',
            'Team member added',
            "{$memberName} has been added to the project team",
            $userId
        );
    }
    
    /**
     * Log team member removal
     */
    public function logTeamMemberRemoved($projectId, $memberName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'team_member_removed',
            'Team member removed',
            "{$memberName} has been removed from the project team",
            $userId
        );
    }
    
    /**
     * Log task creation
     */
    public function logTaskCreated($projectId, $taskName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'task_created',
            'Task created',
            "New task '{$taskName}' has been created",
            $userId
        );
    }
    
    /**
     * Log task update
     */
    public function logTaskUpdated($projectId, $taskName, $progress = null, $userId = null)
    {
        $description = "Task '{$taskName}' has been updated";
        if ($progress !== null) {
            $description .= " (Progress: {$progress}%)";
        }
        
        return $this->logProjectActivity(
            $projectId,
            'task_updated',
            'Task updated',
            $description,
            $userId
        );
    }
    
    /**
     * Log project status change
     */
    public function logStatusChanged($projectId, $fromStatus, $toStatus, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'status_changed',
            'Project status changed',
            "Project status changed from {$fromStatus} to {$toStatus}",
            $userId
        );
    }
    
    /**
     * Log file upload
     */
    public function logFileUploaded($projectId, $fileName, $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'file_uploaded',
            'File uploaded',
            "Document '{$fileName}' uploaded to project",
            $userId
        );
    }
    
    /**
     * Log comment addition
     */
    public function logCommentAdded($projectId, $contextDescription = 'project', $userId = null)
    {
        return $this->logProjectActivity(
            $projectId,
            'comment_added',
            'Comment added',
            "New comment added to {$contextDescription}",
            $userId
        );
    }
}
