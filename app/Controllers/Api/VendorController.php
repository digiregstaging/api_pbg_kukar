<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Vendor;
use Exception;
use Throwable;

class VendorController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on VendorController");
        try {
            $request = [
                'vendor_name' => $this->request->getVar('vendor_name'),
                'director' => $this->request->getVar('director'),
                'address' => $this->request->getVar('address'),
                'kbli_code' => $this->request->getVar('kbli_code'),
                'phone' => $this->request->getVar('phone'),
                'email' => $this->request->getVar('email'),
                'npwp' => $this->request->getVar('npwp'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'vendor_name' => 'required|string',
                "director" => "required|string",
                "address" => "required",
                "kbli_code" => "required|string|is_unique[vendors.kbli_code]",
                "phone" => "required|string|is_unique[vendors.phone]",
                "email" => "required|valid_email|is_unique[vendors.email]",
                "npwp" => "required|string|is_unique[vendors.npwp]",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on VendorController");
                return Response::apiResponse("failed create vendor", $this->validator->getErrors(), 422);
            }


            $data = [
                'vendor_name' => $request['vendor_name'],
                'director' => $request['director'],
                'address' => $request['address'],
                'kbli_code' => $request['kbli_code'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'npwp' => $request['npwp'],
            ];


            $vendorModel = new Vendor();
            $vendorModel->insert($data);


            $data["id"] = $vendorModel->getInsertID();

            log_message("info", "end method store on VendorController");
            return Response::apiResponse("success create vendor", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on VendorController");
        try {
            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($id);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }
            $request = [
                'id' => $id,
                'vendor_name' => $this->request->getVar('vendor_name'),
                'director' => $this->request->getVar('director'),
                'address' => $this->request->getVar('address'),
                'kbli_code' => $this->request->getVar('kbli_code'),
                'phone' => $this->request->getVar('phone'),
                'email' => $this->request->getVar('email'),
                'npwp' => $this->request->getVar('npwp'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'vendor_name' => 'required|string',
                "director" => "required|string",
                "address" => "required",
                "kbli_code" => "required|string|is_unique[vendors.kbli_code,id,{id}]",
                "phone" => "required|string|is_unique[vendors.phone,id,{id}]",
                "email" => "required|valid_email|is_unique[vendors.email,id,{id}]",
                "npwp" => "required|string|is_unique[vendors.npwp,id,{id}]",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on VendorController");
                return Response::apiResponse("failed update vendor", $this->validator->getErrors(), 422);
            }


            $vendor["vendor_name"] = $request['vendor_name'];
            $vendor["director"] = $request['director'];
            $vendor["address"] = $request['address'];
            $vendor["kbli_code"] = $request['kbli_code'];
            $vendor["phone"] = $request['phone'];
            $vendor["email"] = $request['email'];
            $vendor["npwp"] = $request['npwp'];

            $vendorModel->save($vendor);


            log_message("info", "end method update on VendorController");
            return Response::apiResponse("success update vendor", $vendor);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on VendorController");
        log_message("info", $id);
        try {
            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($id);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }

            $vendorModel->delete($id);

            log_message("info", "end method delete on VendorController");
            return Response::apiResponse("success delete vendor", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on VendorController");
        log_message("info", $id);
        try {
            $vendorModel = new Vendor();
            $vendor = $vendorModel->find($id);
            if (!$vendor) {
                throw new Exception("vendor not found");
            }

            log_message("info", "end method get on VendorController");
            return Response::apiResponse("success get vendor", $vendor);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on VendorController");
        try {
            $vendorModel = new Vendor();
            $vendor = $vendorModel->findAll();

            log_message("info", "end method getAll on VendorController");
            return Response::apiResponse("success getAll vendor", $vendor);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
