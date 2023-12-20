<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Kecamatan;
use Exception;
use Throwable;

class KecamatanController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on KecamatanController");
        try {
            $request = [
                'name' => $this->request->getVar('name'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'name' => 'required|string',
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on KecamatanController");
                return Response::apiResponse("failed create kecamatan", $this->validator->getErrors(), 422);
            }


            $data = [
                'name' => $request['name'],
            ];


            $kecamatanModel = new Kecamatan();
            $kecamatanModel->insert($data);


            $data["id"] = $kecamatanModel->getInsertID();

            log_message("info", "end method store on KecamatanController");
            return Response::apiResponse("success create kecamatan", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on KecamatanController");
        try {
            $kecamatanModel = new Kecamatan();
            $kecamatan = $kecamatanModel->find($id);
            if (!$kecamatan) {
                throw new Exception("kecamatan not found");
            }

            $request = [
                'id' => $id,
                'name' => $this->request->getVar('name'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'name' => 'required|string',
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on KecamatanController");
                return Response::apiResponse("failed update kecamatan", $this->validator->getErrors(), 422);
            }


            $kecamatan["name"] = $request['name'];

            $kecamatanModel->save($kecamatan);


            log_message("info", "end method update on KecamatanController");
            return Response::apiResponse("success update Kecamatan", $kecamatan);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on KecamatanController");
        log_message("info", $id);
        try {
            $kecamatanModel = new Kecamatan();
            $kecamatan = $kecamatanModel->find($id);
            if (!$kecamatan) {
                throw new Exception("Kecamatan not found");
            }

            $kecamatanModel->delete($id);

            log_message("info", "end method delete on KecamatanController");
            return Response::apiResponse("success delete Kecamatan", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on KecamatanController");
        log_message("info", $id);
        try {
            $kecamatanModel = new Kecamatan();
            $kecamatan = $kecamatanModel->find($id);
            if (!$kecamatan) {
                throw new Exception("kecamatan not found");
            }

            log_message("info", "end method get on KecamatanController");
            return Response::apiResponse("success get kecamatan", $kecamatan);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on KecamatanController");
        try {
            $kecamatanModel = new Kecamatan();

            $kecamatan = $kecamatanModel
                ->orderBy("name")
                ->findAll();

            log_message("info", "end method getAll on KecamatanController");
            return Response::apiResponse("success getAll kecamatan", $kecamatan);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
