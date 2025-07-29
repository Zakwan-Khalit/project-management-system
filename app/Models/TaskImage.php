<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskImage extends Model
{
    protected $table = 'task_images';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'task_id',
        'file_name',
        'file_address',
        'date_created',
    ];
    protected $useTimestamps = false;
}
