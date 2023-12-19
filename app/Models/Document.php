<?php

namespace App\Models;

use CodeIgniter\Model;

class Document extends Model
{
    protected $table = 'documents_tbl';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        "doc_name",
        "url",
        "type",
        "additional_data_id",
        "ext"
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public static $type = [
        "1" => "vendor_histories",
        "2" => "project_progress",
        "3" => "project_payments",
        "4" => "project_selection"
    ];

    public static $ext = [
        "2" => "image",
        "application/pdf" => "pdf",
    ];
}
