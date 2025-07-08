<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskCommentModel extends Model
{
    protected $table = 'task_comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'user_id', 'comment', 'is_active', 'is_delete'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'task_id' => 'required|integer',
        'user_id' => 'required|integer',
        'comment' => 'required'
    ];
    
    public function getTaskComments($taskId)
    {
        $builder = $this->db->table('task_comments tc');
        $builder->select('
            tc.*,
            u.email,
            up.first_name,
            up.last_name,
            up.avatar
        ');
        $builder->join('users u', 'u.id = tc.user_id AND u.is_delete = 0', 'left');
        $builder->join('user_profile up', 'up.user_id = u.id AND up.is_delete = 0', 'left');
        $builder->where('tc.task_id', $taskId);
        $builder->where('tc.is_active', 1);
        $builder->where('tc.is_delete', 0);
        $builder->orderBy('tc.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    public function addComment($data)
    {
        $data['is_active'] = 1;
        $data['is_delete'] = 0;
        return $this->insert($data);
    }
    
    public function updateComment($id, $data)
    {
        return $this->update($id, $data);
    }
    
    public function deleteComment($id, $userId = null)
    {
        $updateData = [
            'is_delete' => 1
        ];
        
        if ($userId) {
            // Only allow user to delete their own comments or admin
            $comment = $this->find($id);
            if (!$comment || ($comment['user_id'] != $userId && !$this->isAdmin($userId))) {
                return false;
            }
        }
        
        return $this->update($id, $updateData);
    }
    
    public function getCommentCount($taskId)
    {
        return $this->where('task_id', $taskId)
                   ->where('is_active', 1)
                   ->where('is_delete', 0)
                   ->countAllResults();
    }
    
    public function getUserComments($userId, $limit = 10)
    {
        $builder = $this->db->table('task_comments tc');
        $builder->select('
            tc.*,
            t.title as task_title,
            p.name as project_name
        ');
        $builder->join('tasks t', 't.id = tc.task_id AND t.is_delete = 0', 'left');
        $builder->join('projects p', 'p.id = t.project_id AND p.is_delete = 0', 'left');
        $builder->where('tc.user_id', $userId);
        $builder->where('tc.is_active', 1);
        $builder->where('tc.is_delete', 0);
        $builder->orderBy('tc.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    private function isAdmin($userId)
    {
        $builder = $this->db->table('user_role ur');
        $builder->join('user_role_lookup url', 'url.id = ur.role_id');
        $builder->where('ur.user_id', $userId);
        $builder->where('ur.is_active', 1);
        $builder->where('ur.is_delete', 0);
        $builder->where('url.code', 'admin');
        $builder->orWhere('url.code', 'superadmin');
        
        return $builder->countAllResults() > 0;
    }
}
