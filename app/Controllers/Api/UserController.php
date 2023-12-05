<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\User;
use Exception;
use Throwable;

class UserController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on UserController");
        try {
            $request = [
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
                'name' => $this->request->getVar('name'),
                'job' => $this->request->getVar('job'),
                'role' => $this->request->getVar('role'),
                'phone' => $this->request->getVar('phone'),
                'email' => $this->request->getVar('email'),
                'position' => $this->request->getVar('position'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'username' => 'required|is_unique[users.username]',
                "password" => "required|min_length[8]",
                "name" => "required|string",
                "job" => "required|string",
                "role" => "required|integer",
                "phone" => "required|string|is_unique[users.phone]",
                "email" => "required|valid_email|is_unique[users.email]",
                "position" => "required|string",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on UserController");
                return Response::apiResponse("failed create user", $this->validator->getErrors(), 422);
            }

            if (!isset(User::$role[$request["role"]])) {
                throw new Exception("role invalid");
            }


            $data = [
                'username' => $request['username'],
                "password" => password_hash($request['password'], PASSWORD_DEFAULT),
                'name' => $request["name"],
                'job' => $request['job'],
                'role' => $request["role"],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'position' => $request['position'],
            ];


            $userModel = new User();
            $userModel->insert($data);


            $data["id"] = $userModel->getInsertID();

            log_message("info", "end method store on UserController");
            return Response::apiResponse("success create user", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on UserController");
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            if (!$user) {
                throw new Exception("user not found");
            }

            $request = [
                'id' => $id,
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
                'name' => $this->request->getVar('name'),
                'job' => $this->request->getVar('job'),
                'role' => $this->request->getVar('role'),
                'phone' => $this->request->getVar('phone'),
                'email' => $this->request->getVar('email'),
                'position' => $this->request->getVar('position'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'username' => 'required|is_unique[users.username,id,{id}]',
                "password" => "required|min_length[8]",
                "name" => "required|string",
                "job" => "required|string",
                "role" => "required|integer",
                "phone" => "required|string|is_unique[users.phone,id,{id}]",
                "email" => "required|valid_email|is_unique[users.email,id,{id}]",
                "position" => "required|string",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on UserController");
                return Response::apiResponse("failed update user", $this->validator->getErrors(), 422);
            }


            $user["username"] = $request['username'];
            $user["password"] = password_hash($request['password'], PASSWORD_DEFAULT);
            $user["name"] = $request['name'];
            $user["job"] = $request['job'];
            $user["role"] = $request['role'];
            $user["phone"] = $request['phone'];
            $user["email"] = $request['email'];
            $user["position"] = $request['position'];

            $userModel->save($user);


            log_message("info", "end method update on UserController");
            return Response::apiResponse("success update user", $user);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on UserController");
        log_message("info", $id);
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            if (!$user) {
                throw new Exception("user not found");
            }

            $userModel->delete($id);

            log_message("info", "end method delete on UserController");
            return Response::apiResponse("success delete user", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on UserController");
        log_message("info", $id);
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            if (!$user) {
                throw new Exception("user not found");
            }

            $role_name = isset(User::$role[$user["role"]]) ? User::$role[$user["role"]] : "";
            $user["role_name"] = $role_name;
            log_message("info", "end method get on UserController");
            return Response::apiResponse("success get user", $user);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on UserController");
        try {
            $userModel = new User();
            $user = $userModel->findAll();

            $newListUser = [];

            foreach ($user as $key => $value) {
                $role_name = isset(User::$role[$value["role"]]) ? User::$role[$value["role"]] : "";
                $user["role_name"] = $role_name;
                $newListUser[] = $user;
            }

            log_message("info", "end method getAll on UserController");
            return Response::apiResponse("success getAll user", $newListUser);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
