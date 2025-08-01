<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskImage extends Model
{
    // Insert a new image record
    public function addImage($data)
    {
        return $this->db->table('task_images')->insert($data);
    }

    // Get all images for a task
    public function getImagesByTask($taskId)
    {
        return $this->db->table('task_images')
            ->where('task_id', $taskId)
            ->where('is_delete', 0)
            ->orderBy('date_created', 'ASC')
            ->get()->getResultArray();
    }

    // Get a single image by ID
    public function getImage($id)
    {
        return $this->db->table('task_images')
            ->where('id', $id)
            ->where('is_delete', 0)
            ->get()->getRowArray();
    }

    // Soft delete an image by ID
    public function deleteImage($id)
    {
        return $this->db->table('task_images')
            ->where('id', $id)
            ->update(['is_delete' => 1]);
    }
}
