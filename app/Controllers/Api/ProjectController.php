<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Budget;
use App\Models\Program;
use App\Models\Project;
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
            $request = [
                'project_name' => $this->request->getVar('project_name'),
                // 'responsible' => $this->request->getVar('responsible'),
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
            ];

            log_message("info", json_encode($request));

            $rule = [
                'project_name' => 'required|string',
                // "responsible" => "required|string",
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
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on ProjectController");
                return Response::apiResponse("failed create project", $this->validator->getErrors(), 422);
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
            ];


            $projectModel = new Project();
            $projectModel->insert($data);


            $data["id"] = $projectModel->getInsertID();

            log_message("info", "end method store on ProjectController");
            return Response::apiResponse("success create project", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on ProjectController");
        try {
            $projectModel = new Project();
            $project = $projectModel->find($id);
            if (!$project) {
                throw new Exception("project not found");
            }

            $request = [
                'id' => $id,
                'project_name' => $this->request->getVar('project_name'),
                // 'responsible' => $this->request->getVar('responsible'),
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
            ];

            log_message("info", json_encode($request));

            $rule = [
                'project_name' => 'required|string',
                // "responsible" => "required|string",
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
                return Response::apiResponse("failed update project", $this->validator->getErrors(), 422);
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

            $project["project_name"] = $request['project_name'];
            $project["responsible"] = $user["name"];
            $project["start_date"] = $start_date;
            $project["end_date"] = $end_date;
            $project["work_day"] = $request['work_day'];
            $project["vendor_id"] = $request['vendor_id'];
            $project["user_id"] = $request['user_id'];
            $project["budget_id"] = $request['budget_id'];
            $project["program_id"] = $request['program_id'];
            $project["contract_value"] = $request['contract_value'];
            $project["project_code"] = $request['project_code'];
            $project["pugu"] = $request['pugu'];
            $project["status"] = isset(Project::$status[$request["status"]]) ? $request['status'] : 0;
            $project["selection_status"] = isset(Project::$selection_status[$request["selection_status"]]) ? $request['selection_status'] : 0;


            $projectModel->save($project);

            log_message("info", "end method update on ProjectController");
            return Response::apiResponse("success update project", $project);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on ProjectController");
        log_message("info", $id);
        try {
            $projectModel = new Project();
            $project = $projectModel->find($id);
            if (!$project) {
                throw new Exception("project not found");
            }

            $projectModel->delete($id);

            log_message("info", "end method delete on ProjectController");
            return Response::apiResponse("success delete project", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on ProjectController");
        log_message("info", $id);
        try {
            $user_login = $this->request->user;
            log_message("info", json_encode($user_login));

            $projectModel = new Project();
            if ($user_login["role"] == 2) {
                $projectModel = $projectModel->where("user_id", $user_login["id"]);
            }
            $project = $projectModel->find($id);
            if (!$project) {
                throw new Exception("project not found");
            }

            $project["status_name"] = isset(Project::$status[$project["status"]]) ? Project::$status[$project["status"]] : "";

            $progress = 0;
            $projectProgressModel = new ProjectProgress();
            $projectProgress = $projectProgressModel->where("project_id", $project["id"])->findAll();
            foreach ($projectProgress as $key => $value) {
                $progress = $progress + $value["quality"] * $value["progress"];
            }

            $remain = Project::getRemainPayment($project);

            $project["progress_quantity"] = $progress;
            $project["paid"] = $project["contract_value"] - $remain;


            log_message("info", "end method get on ProjectController");
            return Response::apiResponse("success get project", $project);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on ProjectController");
        try {
            $request = [
                'vendor_id' => $this->request->getGet('vendor_id'),
                'year' => $this->request->getGet('year'),
                'user_id' => $this->request->getGet('user_id'),
                'status' => $this->request->getGet('status'),
            ];

            log_message("info", json_encode($request));
            $projectModel = new Project();

            if ($request["vendor_id"]) {
                $projectModel->where("vendor_id", $request["vendor_id"]);
            }

            if ($request["user_id"]) {
                $projectModel->where("user_id", $request["user_id"]);
            }

            if ($request["status"]) {
                $projectModel->where("status", $request["status"]);
            }

            if ($request["year"]) {
                $projectModel->select("projects.*")->join("budgets", "budgets.id = projects.budget_id")
                    ->where("budgets.year", $request["year"]);
            }

            $project = $projectModel->orderBy("id")
                ->findAll();

            $newListProject = [];
            foreach ($project as $key => $value) {
                $value["status_name"] = isset(Project::$status[$value["status"]]) ? Project::$status[$value["status"]] : "";

                $progress = 0;
                $projectProgressModel = new ProjectProgress();
                $projectProgress = $projectProgressModel->where("project_id", $value["id"])->findAll();
                foreach ($projectProgress as $key => $value2) {
                    $progress = $progress + $value2["quality"] * $value2["progress"];
                }

                $value["progress_quantity"] = $progress;
                $newListProject[] = $value;
            }

            log_message("info", "end method getAll on ProjectController");
            return Response::apiResponse("success getAll project", $newListProject);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function updateStatusProject($id = null)
    {
        log_message("info", "start method updateStatusProject on ProjectController");
        log_message("info", $id);
        try {
            $request = [
                'status' => $this->request->getVar('status'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                'status' => 'required|numeric',
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method updateStatusProject on ProjectController");
                return Response::apiResponse("failed update status project", $this->validator->getErrors(), 422);
            }


            $projectModel = new Project();
            $project = $projectModel->find($id);
            if (!$project) {
                throw new Exception("project not found");
            }

            $project["status"] = isset(Project::$status[$request["status"]]) ? $request["status"] : 0;

            $projectModel->save($project);

            log_message("info", "end method updateStatusProject on ProjectController");
            return Response::apiResponse("success update status project", $project);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getDateForGraph($year = 0)
    {
        log_message("info", "start method getDateForGraph on ProjectController");
        try {
            log_message("info", $year);

            $projectModel = new Project();

            $project = $projectModel->select("projects.status, COUNT(projects.id) as count_project")
                ->join("budgets", "budgets.id = projects.budget_id")
                ->where("budgets.year", $year)
                ->groupBy("projects.status")
                ->findAll();

            $count_status = [
                "1" => 0,
                "2" => 0,
                "3" => 0,
            ];

            foreach ($project as $key => $value) {
                $count_status[$value["status"]] += $value["count_project"];
            }

            log_message("info", "end method getDateForGraph on ProjectController");
            return Response::apiResponse("success getDateForGraph project", $count_status);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
