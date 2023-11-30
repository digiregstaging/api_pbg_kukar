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
}
