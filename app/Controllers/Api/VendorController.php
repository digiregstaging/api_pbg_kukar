<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Vendor;
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
                return Response::apiResponse("failed create vendo", $this->validator->getErrors(), 422);
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


            $modelActivity = new Vendor();
            $modelActivity->insert($data);
            
            
            $data["id"] = $modelActivity->getInsertID();

            log_message("info", "end method store on VendorController");
            return Response::apiResponse("success create vendor", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function index()
    {
        try {
            $user_id = $this->request->getGet('user_id');
            $limit_by_day = $this->request->getGet('limit_by_day');
            $object_activity = $this->request->getGet('object_activity');
            $role = $this->request->getGet('role');

            $db = \Config\Database::connect();
            $activity = $db->table("activity")
                ->select("activity.*, users.username, users.name, users.role")
                ->join("users", "users.id = activity.user_id");

            if ($limit_by_day) {
                $activity = $activity->where('activity.created_at >=', date('Y-m-d', strtotime('-' . $limit_by_day . ' days', strtotime(date('Y-m-d')))))
                    ->where('activity.created_at <=', date('Y-m-d H:i:s'));
            }

            if ($user_id) {
                $activity = $activity->where("activity.user_id", $user_id);
            }

            if ($object_activity) {
                $activity = $activity->where("activity.object_activity", $object_activity);
            }

            if ($role) {
                $activity = $activity->where("users.role", $role);
            }

            $activity = $activity->orderBy("activity.created_at", "desc")
                ->get()
                ->getResultArray();

            return Response::apiResponse("success get all activity", $activity);
        } catch (\Throwable $th) {
            return Response::apiResponse($th->getMessage(), [], 400);
        }
    }

    public function getLineChartActivity()
    {
        try {
            $object_activity = [
                "login" => 0,
                "logout" => 0,
                "practice" => 0,
                "course" => 0,
                "cheatsheet" => 0,
                "teacher" => 0,
                "student" => 0
            ];

            $db = \Config\Database::connect();

            $query = $db->table('activity')
                ->select('*, DATE(activity.created_at) as date')
                ->where('created_at >=', date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-d')))))
                ->where('created_at <=', date('Y-m-d H:i:s'))
                ->where("object_activity !=", "");

            $results = $query->get()->getResultArray();


            $response = [];
            foreach ($results as $key => $value) {
                if (!isset($response[$value["date"]])) {
                    $response[$value["date"]] = [
                        "login" => 0,
                        "logout" => 0,
                        "practice" => 0,
                        "course" => 0,
                        "cheatsheet" => 0,
                        "teacher" => 0,
                        "student" => 0
                    ];
                }

                if (isset($object_activity[$value["object_activity"]])) {
                    $response[$value["date"]][$value["object_activity"]] += 1;
                }
            }

            return Response::apiResponse("success get data for line chart activity", $response);
        } catch (\Throwable $th) {
            return Response::apiResponse($th->getMessage(), [], 400);
        }
    }
}
