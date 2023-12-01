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

    public static function getRemainPayment($project)
    {
        $budgetModel = new Budget();
        $budget = $budgetModel->find($project["budget_id"]);

        $projectPaymentModel = new ProjectPayment();
        $list_payment = $projectPaymentModel->where("project_id", $project["id"])
            ->findAll();

        $remain = $budget["value"];
        foreach ($list_payment as $key => $value) {
            $remain = $remain - $value["fee_pay"];
        }

        return $remain;
    }
}
