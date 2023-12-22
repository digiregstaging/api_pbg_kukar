<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Throwable;

class AuthController extends BaseController
{
    public function login()
    {
        log_message("info", "start method login on AuthController");
        try {
            $request = [
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'username' => 'required|string',
                "password" => "required|string",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method login on AuthController");
                return Response::apiResponse("failed login", $this->validator->getErrors(), 422);
            }

            $modelUser = new User();

            $user = $modelUser->where("username", $request["username"])->first();

            $is_valid = 0;
            if ($user) {
                $password_verify = password_verify($request["password"], $user["password"]);
                if ($password_verify) {
                    $is_valid = 1;
                }
            }

            if ($is_valid == 0) {
                throw new Exception("username or password invalid");
            }



            $key = getenv("JWT_SECRET_KEY");
            $uuid = service('uuid');
            $uuid4 = $uuid->uuid4();
            $string_uuid = $uuid4->toString();
            $payload = [
                'sub' => $user["id"],
                'iss' => 'pm_kukar',
                'iat' => time(),
                'exp' => time() + 3600 * 24,
                'aud' => '',
                'jti' => $string_uuid,
                'role' => $user["role"]
            ];

            $token = JWT::encode($payload, $key, "HS256");

            $user["token"] = $token;

            $modelUser->save($user);

            unset($user["password"]);

            log_message("info", "end method login on AuthController");
            return Response::apiResponse("success login", $user);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }


    public function logout()
    {
        log_message("info", "start method logout on AuthController");
        try {
            $modelUser = new User();
            $user_login = $this->request->user;

            $user = $modelUser->where("token", $user_login["token"])->first();

            if ($user) {
                $user["token"] = null;
                $modelUser->save($user);
            }


            log_message("info", "end method logout on AuthController");
            return Response::apiResponse("success logout", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function resetPassword($id = null)
    {
        log_message("info", "start method resetPassword on AuthController");
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            if (!$user) {
                throw new Exception("user not found");
            }

            $request = [
                'id' => $id,
                'password' => $this->request->getVar('password'),
                'confirm_password' => $this->request->getVar('confirm_password'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'password' => 'required|string|min_length[8]',
                "confirm_password" => "required|string|min_length[8]",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method resetPassword on AuthController");
                return Response::apiResponse("failed reset password", $this->validator->getErrors(), 422);
            }

            if ($request["password"] != $request["confirm_password"]) {
                throw new Exception("password and confirm password not match");
            }


            $user["password"] = password_hash($request['password'], PASSWORD_DEFAULT);

            $userModel->save($user);


            log_message("info", "end method resetPassword on AuthController");
            return Response::apiResponse("success reset password", $user);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
