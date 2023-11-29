<?php

namespace App\Models;

use CodeIgniter\Model;

class Program extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "program",
        "activity",
        "sub_activity",
        "description"
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
