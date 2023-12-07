<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Program;
use Exception;
use Throwable;

class ProgramController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on ProgramController");
        try {
            $request = [
                'program' => $this->request->getVar('program'),
                'activity' => $this->request->getVar('activity'),
                'sub_activity' => $this->request->getVar('sub_activity'),
                'description' => $this->request->getVar('description'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'program' => 'required|string',
                "activity" => "required|string",
                "sub_activity" => "required|string",
                "description" => "required",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on ProgramController");
                return Response::apiResponse("failed create program", $this->validator->getErrors(), 422);
            }


            $data = [
                'program' => $request['program'],
                'activity' => $request["activity"],
                'sub_activity' => $request['sub_activity'],
                'description' => $request['description'],
            ];


            $programModel = new Program();
            $programModel->insert($data);


            $data["id"] = $programModel->getInsertID();

            log_message("info", "end method store on ProgramController");
            return Response::apiResponse("success create program", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on ProgramController");
        try {
            $programModel = new Program();
            $program = $programModel->find($id);
            if (!$program) {
                throw new Exception("program not found");
            }

            $request = [
                'id' => $id,
                'program' => $this->request->getVar('program'),
                'activity' => $this->request->getVar('activity'),
                'sub_activity' => $this->request->getVar('sub_activity'),
                'description' => $this->request->getVar('description'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'program' => 'required|string',
                "activity" => "required|string",
                "sub_activity" => "required|string",
                "description" => "required",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on ProgramController");
                return Response::apiResponse("failed update program", $this->validator->getErrors(), 422);
            }


            $program["program"] = $request['program'];
            $program["activity"] = $request['activity'];
            $program["sub_activity"] = $request['sub_activity'];
            $program["description"] = $request['description'];

            $programModel->save($program);


            log_message("info", "end method update on ProgramController");
            return Response::apiResponse("success update program", $program);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on ProgramController");
        log_message("info", $id);
        try {
            $programModel = new Program();
            $program = $programModel->find($id);
            if (!$program) {
                throw new Exception("program not found");
            }

            $programModel->delete($id);

            log_message("info", "end method delete on ProgramController");
            return Response::apiResponse("success delete program", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on ProgramController");
        log_message("info", $id);
        try {
            $programModel = new Program();
            $program = $programModel->find($id);
            if (!$program) {
                throw new Exception("program not found");
            }

            log_message("info", "end method get on ProgramController");
            return Response::apiResponse("success get program", $program);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on ProgramController");
        try {
            $request = [
                'program' => $this->request->getGet('program'),
                'activity' => $this->request->getGet('activity'),
                'sub_activity' => $this->request->getGet('sub_activity'),
            ];

            log_message("info", json_encode($request));

            $programModel = new Program();
            if ($request["program"]) {
                $programModel->like("program", $request["program"], 'both', null, true);
            }

            if ($request["activity"]) {
                $programModel->like("activity", $request["activity"], 'both', null, true);
            }

            if ($request["sub_activity"]) {
                $programModel->like("sub_activity", $request["sub_activity"], 'both', null, true);
            }

            $program = $programModel->findAll();

            log_message("info", "end method getAll on ProgramController");
            return Response::apiResponse("success getAll program", $program);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
