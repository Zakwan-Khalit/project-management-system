<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskCommentModel extends Model
{
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
        $builder = $this->db->table('task_comments');
        return $builder->insert($data);
    }

    public function updateComment($id, $data)
    {
        $builder = $this->db->table('task_comments');
        return $builder->where('id', $id)->update($data);
    }

    public function deleteComment($id, $userId = null)
    {
        $updateData = [
            'is_delete' => 1
        ];
        if ($userId) {
            $updateData['deleted_by'] = $userId;
        }
        $builder = $this->db->table('task_comments');
        return $builder->where('id', $id)->update($updateData);
    }

    public function getCommentCount($taskId)
    {
        $builder = $this->db->table('task_comments');
        return $builder->where('task_id', $taskId)
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
        $builder->groupStart();
        $builder->where('url.code', 'admin');
        $builder->orWhere('url.code', 'superadmin');
        $builder->groupEnd();
        return $builder->countAllResults() > 0;
    }
}
