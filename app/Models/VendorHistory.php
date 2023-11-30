<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorHistory extends Model
{
    protected $table = 'vendor_histories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "task_name",
        "status",
        "vendor_id",
        "project_id"
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
