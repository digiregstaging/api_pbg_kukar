<?php

namespace App\Models;

use CodeIgniter\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "project_name",
        "responsible",
        "start_date",
        "end_date",
        "work_day",
        "vendor_id",
        "user_id",
        "budget_id",
        "program_id",
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
