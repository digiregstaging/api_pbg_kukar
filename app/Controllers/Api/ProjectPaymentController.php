<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Project;
use App\Models\ProjectPayment;
use Exception;
use Throwable;

class ProjectPaymentController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on ProjectPaymentController");
        try {
            $request = [
                'termin' => $this->request->getVar('termin'),
                'quality_pay' => $this->request->getVar('quality_pay'),
                // 'status' => $this->request->getVar('status'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'termin' => 'required|string',
                "quality_pay" => "required|numeric",
                // "status" => "required|integer",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on ProjectPaymentController");
                return Response::apiResponse("failed create project payment", $this->validator->getErrors(), 422);
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }


            $data = [
                'termin' => $request['termin'],
                'quality_pay' => $request["quality_pay"],
                'fee_pay' => 0,
                // 'status' => $request["status"],
                'project_id' => $request['project_id'],
            ];

            $projectPaymentModel = new ProjectPayment();

            $projectPaymentModel->insert($data);

            $data["id"] = $projectPaymentModel->getInsertID();

            log_message("info", "end method store on ProjectPaymentController");
            return Response::apiResponse("success create project payment", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on ProjectPaymentController");
        try {
            $projectPaymentModel = new ProjectPayment();
            $projectPayment = $projectPaymentModel->find($id);
            if (!$projectPayment) {
                throw new Exception("project payment not found");
            }

            $request = [
                'id' => $id,
                'termin' => $this->request->getVar('termin'),
                'quality_pay' => $this->request->getVar('quality_pay'),
                'status' => $this->request->getVar('status'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                'termin' => 'required|string',
                "quality_pay" => "required|numeric",
                "status" => "required|integer",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on ProjectPaymentController");
                return Response::apiResponse("failed update project payment", $this->validator->getErrors(), 422);
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }


            if (!isset(ProjectPayment::$status[$request["status"]])) {
                throw new Exception("invalid status");
            }

            $projectPayment["termin"] = $request['termin'];
            $projectPayment["quality_pay"] = $request['quality_pay'];
            $projectPayment["fee_pay"] = 0;
            $projectPayment["status"] = $request["status"];
            $projectPayment["project_id"] = $request['project_id'];
            $projectPaymentModel->save($projectPayment);

            // $remain = Project::getRemainPayment($project);
            // if ($remain < 0) {
            //     throw new Exception("invalid input fee pay");
            // }

            log_message("info", "end method update on ProjectPaymentController");
            return Response::apiResponse("success update project payment", $projectPayment);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on ProjectPaymentController");
        log_message("info", $id);
        try {
            $projectPaymentModel = new ProjectPayment();
            $projectPayment = $projectPaymentModel->find($id);
            if (!$projectPayment) {
                throw new Exception("project payment not found");
            }

            $projectPaymentModel->delete($id);

            log_message("info", "end method delete on ProjectPaymentController");
            return Response::apiResponse("success delete project payment", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on ProjectPaymentController");
        log_message("info", $id);
        try {
            $projectPaymentModel = new ProjectPayment();
            $projectPayment = $projectPaymentModel->find($id);
            if (!$projectPayment) {
                throw new Exception("project payment not found");
            }

            log_message("info", "end method get on ProjectPaymentController");
            return Response::apiResponse("success get project payment", $projectPayment);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on ProjectPaymentController");
        try {
            $request = [
                'project_id' => $this->request->getGet('project_id'),
            ];

            log_message("info", json_encode($request));
            $projectPaymentModel = new ProjectPayment();
            $projectPaymentModel = $projectPaymentModel->select("project_payments.*, projects.contract_value")
                ->join("projects", "projects.id = project_payments.project_id");

            if ($request["project_id"]) {
                $projectPaymentModel->where("project_payments.project_id", $request["project_id"]);
            }
            $projectPayment = $projectPaymentModel
                ->orderBy("id")
                ->findAll();

            $newProjectPaymet = [];

            foreach ($projectPayment as $key => $value) {
                $value["value"] = $value["quality_pay"] * $value["contract_value"];
                $newProjectPaymet[] = $value;
            }

            log_message("info", "end method getAll on ProjectPaymentController");
            return Response::apiResponse("success getAll project payment", $newProjectPaymet);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function updateStatusProjectPayment($id = null)
    {
        log_message("info", "start method updateStatusProjectPayment on ProjectPaymentController");
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
                log_message("info", "validation error method updateStatusProjectPayment on ProjectPaymentController");
                return Response::apiResponse("failed update status project payment", $this->validator->getErrors(), 422);
            }


            $projectPaymentModel = new ProjectPayment();
            $projectPayment = $projectPaymentModel->find($id);
            if (!$projectPayment) {
                throw new Exception("project payment not found");
            }

            $projectPayment["status"] = isset(ProjectPayment::$status[$request["status"]]) ? $request["status"] : 0;

            $projectPaymentModel->save($projectPayment);

            log_message("info", "end method updateStatusProjectPayment on ProjectPaymentController");
            return Response::apiResponse("success update status project payment", $projectPayment);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
