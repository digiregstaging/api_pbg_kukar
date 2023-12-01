<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectPayment extends Model
{
    public static $status = [
        "1" => "paid"
    ];

    protected $table = 'project_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "termin",
        "quality_pay",
        "fee_pay",
        "status",
        "project_id",
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
