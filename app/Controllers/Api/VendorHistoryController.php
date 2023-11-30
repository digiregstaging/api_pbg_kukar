<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\VendorHistory;
use Exception;
use Throwable;

class VendorHistoryController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on VendorHistoryController");
        try {
            $request = [
                'task_name' => $this->request->getVar('task_name'),
                'status' => $this->request->getVar('status'),
                'vendor_id' => $this->request->getVar('vendor_id'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'task_name' => 'required|string',
                "status" => "required|integer",
                "vendor_id" => "required|integer",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on VendorHistoryController");
                return Response::apiResponse("failed create vendor history", $this->validator->getErrors(), 422);
            }

            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($request["vendor_id"]);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }

            $data = [
                'task_name' => $request['task_name'],
                'status' => $request["status"],
                'vendor_id' => $request['vendor_id'],
                'project_id' => $request['project_id'],
            ];


            $vendorHistoryModel = new VendorHistory();
            $vendorHistoryModel->insert($data);


            $data["id"] = $vendorHistoryModel->getInsertID();

            log_message("info", "end method store on VendorHistoryController");
            return Response::apiResponse("success create vendor history", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on VendorHistoryController");
        try {
            $vendorHistoryModel = new VendorHistory();
            $vendorHistory = $vendorHistoryModel->find($id);
            if (!$vendorHistory) {
                throw new Exception("vendor history not found");
            }

            $request = [
                'id' => $id,
                'task_name' => $this->request->getVar('task_name'),
                'status' => $this->request->getVar('status'),
                'vendor_id' => $this->request->getVar('vendor_id'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                'task_name' => 'required|string',
                "status" => "required|integer",
                "vendor_id" => "required|integer",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on VendorHistoryController");
                return Response::apiResponse("failed update vendor history", $this->validator->getErrors(), 422);
            }

            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($request["vendor_id"]);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }


            $vendorHistory["task_name"] = $request['task_name'];
            $vendorHistory["status"] = $request['status'];
            $vendorHistory["vendor_id"] = $request['vendor_id'];
            $vendorHistory["project_id"] = $request['project_id'];

            $vendorHistoryModel->save($vendorHistory);


            log_message("info", "end method update on VendorHistoryController");
            return Response::apiResponse("success update vendor history", $vendorHistory);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on VendorHistoryController");
        log_message("info", $id);
        try {
            $vendorHistoryModel = new VendorHistory();
            $vendorHistory = $vendorHistoryModel->find($id);
            if (!$vendorHistory) {
                throw new Exception("vendor history not found");
            }

            $vendorHistoryModel->delete($id);

            log_message("info", "end method delete on VendorHistoryController");
            return Response::apiResponse("success delete vendor history", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on VendorHistoryController");
        log_message("info", $id);
        try {
            $vendorHistoryModel = new VendorHistory();
            $vendorHistory = $vendorHistoryModel->find($id);
            if (!$vendorHistory) {
                throw new Exception("vendor history not found");
            }

            log_message("info", "end method get on VendorHistoryController");
            return Response::apiResponse("success get vendor history", $vendorHistory);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on VendorHistoryController");
        try {
            $vendorHistoryModel = new VendorHistory();
            $vendorHistory = $vendorHistoryModel->findAll();

            log_message("info", "end method getAll on VendorHistoryController");
            return Response::apiResponse("success getAll vendor history", $vendorHistory);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
