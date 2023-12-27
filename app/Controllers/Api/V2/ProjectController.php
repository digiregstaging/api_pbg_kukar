<?php

namespace App\Controllers\Api\V2;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Budget;
use App\Models\Kecamatan;
use App\Models\Program;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\ProjectProgress;
use App\Models\User;
use App\Models\Vendor;
use Exception;
use Throwable;

class ProjectController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on ProjectController");
        try {
            $db = \Config\Database::connect();
            $db->transBegin();
            $request = [
                'project_name' => $this->request->getVar('project_name'),
                'kecamatan_id' => $this->request->getVar('kecamatan_id'),
                'detail_location' => $this->request->getVar('detail_location'),
                'start_date' => $this->request->getVar('start_date'),
                'end_date' => $this->request->getVar('end_date'),
                'work_day' => $this->request->getVar('work_day'),
                'vendor_id' => $this->request->getVar('vendor_id'),
                'user_id' => $this->request->getVar('user_id'),
                'budget_id' => $this->request->getVar('budget_id'),
                'program_id' => $this->request->getVar('program_id'),
                'contract_value' => $this->request->getVar('contract_value'),
                'project_code' => $this->request->getVar('project_code'),
                'pugu' => $this->request->getVar('pugu'),
                'status' => $this->request->getVar('status'),
                'selection_status' => $this->request->getVar('selection_status'),
                'progress' => $this->request->getVar('progress'),
                'payments' => $this->request->getVar('payments'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'project_name' => 'required|string',
                "kecamatan_id" => "required|integer",
                "detail_location" => "required",
                "start_date" => "required|string",
                "end_date" => "required|string",
                "work_day" => "required|integer",
                "vendor_id" => "required|integer",
                "user_id" => "required|integer",
                "budget_id" => "required|integer",
                "program_id" => "required|integer",
                "contract_value" => "required|integer",
                "project_code" => "required|string",
                "pugu" => "required|string",
                "status" => "required|integer",
                "selection_status" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on ProjectController");
                return Response::apiResponse("failed create project", $this->validator->getErrors(), 422);
            }

            $kecamatanModel = new Kecamatan();
            $kecamatan = $kecamatanModel->find($request["kecamatan_id"]);
            if (!$kecamatan) {
                throw new Exception("kecamatan not found");
            }

            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($request["vendor_id"]);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }

            $userModel = new User();
            $user = $userModel->find($request["user_id"]);
            if (!$user) {
                throw new Exception("user not found");
            }

            $budgetModel = new Budget();
            $budget = $budgetModel->find($request["budget_id"]);
            if (!$budget) {
                throw new Exception("budget not found");
            }

            $programModel = new Program();
            $program = $programModel->find($request["program_id"]);
            if (!$program) {
                throw new Exception("program not found");
            }

            $start_date = date("Y-m-d H:i:s", strtotime($request["start_date"]));
            $end_date = date("Y-m-d H:i:s", strtotime($request["end_date"]));

            $data = [
                'project_name' => $request['project_name'],
                'kecamatan_id' => $request['kecamatan_id'],
                'detail_location' => $request['detail_location'],
                'responsible' => $user["name"],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'work_day' => $request['work_day'],
                'vendor_id' => $request['vendor_id'],
                'user_id' => $request['user_id'],
                'budget_id' => $request['budget_id'],
                'program_id' => $request['program_id'],
                'contract_value' => $request['contract_value'],
                'project_code' => $request['project_code'],
                'pugu' => $request['pugu'],
                "status" => isset(Project::$status[$request["status"]]) ? $request['status'] : 0,
                "selection_status" => isset(Project::$selection_status[$request["selection_status"]]) ? $request['selection_status'] : 0,
            ];


            $projectModel = new Project();
            $projectModel->insert($data);

            if (count($request["progress"]) > 0) {
                foreach ($request["progress"] as $key => $p) {
                    $rule = [
                        'step' => 'required',
                        "quality" => "required|numeric",
                        "progress" => "required|numeric",
                        "status" => "required|numeric",
                    ];

                    if (!$this->validateData((array)$p, $rule)) {
                        log_message("info", "validation error method store on ProjectProgressController");
                        $db->transRollback();
                        return Response::apiResponse("failed create project progress", $this->validator->getErrors(), 422);
                    }

                    $data = [
                        'step' => $p->step,
                        'quality' => $p->quality,
                        'progress' => $p->progress,
                        'status' => $p->status,
                        'project_id' => $projectModel->getInsertID(),
                    ];

                    $projectProgressModel = new ProjectProgress();

                    $projectProgressModel->insert($data);
                }
            }

            if (count($request["payments"]) > 0) {
                foreach ($request["payments"] as $key => $p) {
                    $rule = [
                        'termin' => 'required|string',
                        'status' => 'required|numeric',
                        "quality_pay" => "required|numeric",
                    ];

                    if (!$this->validateData((array)$p, $rule)) {
                        log_message("info", "validation error method store on ProjectProgressController");
                        $db->transRollback();
                        return Response::apiResponse("failed create project payments", $this->validator->getErrors(), 422);
                    }

                    $data = [
                        'termin' => $p->termin,
                        'quality_pay' => $p->quality_pay,
                        'fee_pay' => 0,
                        'status' => $p->status,
                        'project_id' => $projectModel->getInsertID(),
                    ];

                    $projectPaymentsModel = new ProjectPayment();

                    $projectPaymentsModel->insert($data);
                }
            }


            $data["id"] = $projectModel->getInsertID();

            log_message("info", "end method store on ProjectController");
            $db->transCommit();
            return Response::apiResponse("success create project", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            $db->transRollback();
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
