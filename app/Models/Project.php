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
        "status",
        "contract_value",
        "project_code",
        "pugu",
        "selection_status",
        "kecamatan_id",
        "detail_location"
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public static function getRemainPayment($project)
    {
        $projectPaymentModel = new ProjectPayment();
        $list_payment = $projectPaymentModel->where("status", 2)->where("project_id", $project["id"])
            ->findAll();

        $remain = $project["contract_value"];
        foreach ($list_payment as $key => $value) {
            $remain = $remain - $value["quality_pay"] * $project["contract_value"];
        }

        return $remain;
    }

    public static $status = [
        "1" => "Pemilihan",
        "2" => "Berlangsung",
        "3" => "Selesai"
    ];

    public static $selection_status = [
        "1" => "Berlangsung",
        "2" => "Selesai",
    ];
}
