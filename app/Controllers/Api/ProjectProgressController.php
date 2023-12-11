<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Project;
use App\Models\ProjectProgress;
use Exception;
use Throwable;

class ProjectProgressController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on ProjectProgressController");
        try {
            $request = [
                'step' => $this->request->getVar('step'),
                'quality' => $this->request->getVar('quality'),
                'progress_in_percent' => $this->request->getVar('progress_in_percent'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'step' => 'required',
                "quality" => "required|numeric",
                "progress_in_percent" => "required|numeric",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on ProjectProgressController");
                return Response::apiResponse("failed create project progress", $this->validator->getErrors(), 422);
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }

            $data = [
                'step' => $request['step'],
                'quality' => $request["quality"],
                'progress_in_percent' => $request["progress_in_percent"],
                'project_id' => $request['project_id'],
            ];

            $projectProgressModel = new ProjectProgress();

            $projectProgressModel->insert($data);

            $data["id"] = $projectProgressModel->getInsertID();

            log_message("info", "end method store on ProjectProgressController");
            return Response::apiResponse("success create project progress", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on ProjectProgressController");
        try {
            $projectProgressModel = new ProjectProgress();
            $projectProgress = $projectProgressModel->find($id);
            if (!$projectProgress) {
                throw new Exception("project progress not found");
            }

            $request = [
                'id' => $id,
                'step' => $this->request->getVar('step'),
                'quality' => $this->request->getVar('quality'),
                'project_id' => $this->request->getVar('project_id'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                'step' => 'required|integer',
                "quality" => "required|integer",
                "project_id" => "required|integer",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on ProjectProgressController");
                return Response::apiResponse("failed update project progress", $this->validator->getErrors(), 422);
            }

            $projectModel = new Project();
            $project = $projectModel->find($request["project_id"]);
            if (!$project) {
                throw new Exception("project not found");
            }

            $isExistsprojectProgress = $projectProgressModel
                ->where("project_id", $request["project_id"])
                ->where("step", $request["step"])
                ->where("id !=", $id)
                ->first();

            if ($isExistsprojectProgress) {
                throw new Exception("project progress for this project already exists on step " . $request["step"]);
            }

            $projectProgress["step"] = $request['step'];
            $projectProgress["quality"] = $request['quality'];
            $projectProgress["project_id"] = $request['project_id'];
            $projectProgressModel->save($projectProgress);


            log_message("info", "end method update on ProjectProgressController");
            return Response::apiResponse("success update project progress", $projectProgress);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on ProjectProgressController");
        log_message("info", $id);
        try {
            $projectProgressModel = new ProjectProgress();
            $projectProgress = $projectProgressModel->find($id);
            if (!$projectProgress) {
                throw new Exception("project progress not found");
            }

            $projectProgressModel->delete($id);

            log_message("info", "end method delete on ProjectProgressController");
            return Response::apiResponse("success delete project progress", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on ProjectProgressController");
        log_message("info", $id);
        try {
            $projectProgressModel = new ProjectProgress();
            $projectProgress = $projectProgressModel->find($id);
            if (!$projectProgress) {
                throw new Exception("project progress not found");
            }

            log_message("info", "end method get on ProjectProgressController");
            return Response::apiResponse("success get project progress", $projectProgress);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on ProjectProgressController");
        try {
            $request = [
                'project_id' => $this->request->getGet('project_id'),
            ];

            log_message("info", json_encode($request));

            $projectProgressModel = new ProjectProgress();
            if ($request["project_id"]) {
                $projectProgressModel->where("project_id", $request["project_id"]);
            }
            $projectProgress = $projectProgressModel->findAll();

            log_message("info", "end method getAll on ProjectProgressController");
            return Response::apiResponse("success getAll project progress", $projectProgress);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
