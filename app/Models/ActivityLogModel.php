<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    // No protected properties or constructor

    public function logActivity($data)
    {
        // Validate required fields
        if (empty($data['action'])) {
            log_message('error', 'ActivityLogModel::logActivity - Missing required action field');
            return false;
        }

        // Convert arrays to JSON for storage
        if (isset($data['old_values']) && is_array($data['old_values'])) {
            $data['old_values'] = json_encode($data['old_values']);
        }
        if (isset($data['new_values']) && is_array($data['new_values'])) {
            $data['new_values'] = json_encode($data['new_values']);
        }

        // Set default values
        if (!isset($data['user_id'])) {
            $userData = session('userdata');
            $data['user_id'] = $userData['id'] ?? null;
        }
        if (!isset($data['ip_address'])) {
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
        }
        if (!isset($data['user_agent'])) {
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;
        }
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['is_active'] = 1;
        $data['is_delete'] = 0;

        $builder = $this->db->table('activity_logs');
        return $builder->insert($data);
    }

    public function getActivityLogs($filters = [])
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('
            activity_logs.*,
            user_profile.first_name,
            user_profile.last_name,
            user_profile.avatar
        ');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.is_delete', 0);

        if (isset($filters['user_id'])) {
            $builder->where('activity_logs.user_id', $filters['user_id']);
        }
        if (isset($filters['table_name'])) {
            $builder->where('activity_logs.table_name', $filters['table_name']);
        }
        if (isset($filters['record_id'])) {
            $builder->where('activity_logs.record_id', $filters['record_id']);
        }
        if (isset($filters['action'])) {
            $builder->where('activity_logs.action', $filters['action']);
        }
        if (isset($filters['date_from'])) {
            $builder->where('activity_logs.date_created >=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $builder->where('activity_logs.date_created <=', $filters['date_to']);
        }
        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }

        $builder->orderBy('activity_logs.date_created', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getUserActivity($userId, $limit = 10)
    {
        $builder = $this->db->table('activity_logs');
        $builder->where('user_id', $userId);
        $builder->where('is_delete', 0);
        $builder->orderBy('date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    public function getProjectActivity($projectId, $limit = 50)
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('
            activity_logs.*,
            user_profile.first_name,
            user_profile.last_name,
            user_profile.avatar
        ');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.table_name', 'projects');
        $builder->where('activity_logs.record_id', $projectId);
        $builder->where('activity_logs.is_delete', 0);
        $builder->orderBy('activity_logs.date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    public function getTaskActivity($taskId, $limit = 30)
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('
            activity_logs.*,
            user_profile.first_name,
            user_profile.last_name,
            user_profile.avatar
        ');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.table_name', 'tasks');
        $builder->where('activity_logs.record_id', $taskId);
        $builder->where('activity_logs.is_delete', 0);
        $builder->orderBy('activity_logs.date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    public function getRecentActivity($limit = 20)
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('
            activity_logs.*,
            user_profile.first_name,
            user_profile.last_name,
            user_profile.avatar
        ');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.is_delete', 0);
        $builder->where('activity_logs.is_active', 1);
        $builder->orderBy('activity_logs.date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    public function getRecentActivityWithUsers($limit = 15)
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('activity_logs.*, user_profile.first_name, user_profile.last_name, user_profile.avatar');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->orderBy('activity_logs.date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    public function deleteOldLogs($daysOld = 365)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));
        $builder = $this->db->table('activity_logs');
        $builder->where('date_created <', $cutoffDate);
        return $builder->update(['is_delete' => 1]);
    }
}
