<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "username",
        "password",
        "name",
        "job",
        "role",
        "phone",
        "email",
        "token",
        "position",
        "otp",
        "expired_otp",
        "attempt_to_verify"
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public static $role = [
        "1" => "Administrator",
        "2" => "User",
    ];
}
