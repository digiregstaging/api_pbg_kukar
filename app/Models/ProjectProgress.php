<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectProgress extends Model
{
    protected $table = 'project_progress';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "step",
        "quality",
        "project_id",
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
