<?php

namespace App\Models;

use CodeIgniter\Model;

class Budget extends Model
{
    protected $table = 'budgets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "source",
        "year",
        "value",
        "description",
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
