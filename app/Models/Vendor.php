<?php

namespace App\Models;

use CodeIgniter\Model;

class Vendor extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "vendor_name",
        "director",
        "address",
        "kbli_code",
        'phone',
        'email',
        'npwp',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
