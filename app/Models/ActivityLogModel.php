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
        // Only use allowed fields
        $insert = [
            'user_id'      => $data['user_id'] ?? (session('userdata')['id'] ?? null),
            'action'       => $data['action'],
            'details'      => $data['details'] ?? null,
            'is_active'    => 1,
            'is_delete'    => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified'=> date('Y-m-d H:i:s'),
        ];
        $builder = $this->db->table('activity_logs');
        return $builder->insert($insert);
    }

    public function getActivityLogs($filters = [])
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('activity_logs.*, user_profile.first_name, user_profile.last_name');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.is_delete', 0);
        if (isset($filters['user_id'])) {
            $builder->where('activity_logs.user_id', $filters['user_id']);
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
        $builder->select('activity_logs.*, user_profile.first_name, user_profile.last_name');
        $builder->join('user_profile', 'user_profile.user_id = activity_logs.user_id AND user_profile.is_delete = 0', 'left');
        $builder->where('activity_logs.user_id', $userId);
        $builder->where('activity_logs.is_delete', 0);
        $builder->orderBy('activity_logs.date_created', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }

    // getProjectActivity is not supported by schema (no table_name/record_id)
    // You may filter by action or user_id if needed

    // getTaskActivity is not supported by schema (no table_name/record_id)

    public function getRecentActivity($limit = 20)
    {
        $builder = $this->db->table('activity_logs');
        $builder->select('activity_logs.*, user_profile.first_name, user_profile.last_name');
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
        $builder->select('activity_logs.*, user_profile.first_name, user_profile.last_name');
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
