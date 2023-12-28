<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Otp;
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

            $otp = Otp::generateOtp(6);

            $to = $user["email"];
            $subject = "Login OTP";
            $message = "your OTP is " . $otp;

            $email = \Config\Services::email();
            $email->setTo($to);
            $email->setFrom(getenv("email.fromMail"), getenv("email.fromName"));

            $email->setSubject($subject);
            $email->setMessage($message);
            if ($email->send()) {
                log_message("info", "success send otp to " . $user["email"]);
            } else {
                log_message("warning", "failed send otp to " . $user["email"]);
                $data = $email->printDebugger(['headers']);
                log_message("info", json_encode($data));
            }

            $user["otp"] = $otp;
            $user["expired_otp"] = time() + 120;
            $user["attempt_to_verify"] = 3;

            $modelUser->save($user);

            log_message("info", "end method login on AuthController");
            return Response::apiResponse("otp telah dikirim ke email " . $user["email"] . " please check your email");
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

    public function changePassword($id = null)
    {
        log_message("info", "start method changePassword on AuthController");
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            if (!$user) {
                throw new Exception("user not found");
            }

            $request = [
                'id' => $id,
                'old_password' => $this->request->getVar('old_password'),
                'new_password' => $this->request->getVar('new_password'),
                'confirm_new_password' => $this->request->getVar('confirm_new_password'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'old_password' => 'required|string',
                'new_password' => 'required|string|min_length[8]',
                "confirm_new_password" => "required|string|min_length[8]",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method changePassword on AuthController");
                return Response::apiResponse("failed change password", $this->validator->getErrors(), 422);
            }

            if ($request["new_password"] != $request["confirm_new_password"]) {
                throw new Exception("password and confirm password not match");
            }

            $password_verify = password_verify($request["old_password"], $user["password"]);
            if (!$password_verify) {
                throw new Exception("password user invalid");
            }

            $user["password"] = password_hash($request['new_password'], PASSWORD_DEFAULT);

            $userModel->save($user);


            log_message("info", "end method changePassword on AuthController");
            return Response::apiResponse("success change password", $user);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function verifyOtp($email = null)
    {
        log_message("info", "start method verifyOtp on AuthController");
        try {
            $request = [
                "email" => $email,
                'otp' => $this->request->getVar('otp'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'otp' => 'required|string',
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method verifyOtp on AuthController");
                return Response::apiResponse("failed verify", $this->validator->getErrors(), 422);
            }

            $userModel = new User();
            $user = $userModel->where("email", $email)
                ->first();

            if (!$user) {
                throw new Exception("invalid user");
            }


            if (
                $user["attempt_to_verify"] > 0 && $user["expired_otp"] > time()
            ) {
                if ($user["otp"] != $request["otp"]) {
                    $user["attempt_to_verify"] -= 1;
                    $userModel->save($user);
                    throw new Exception("invalid otp");
                }
            } else {
                $user["attempt_to_verify"] = null;
                $user["otp"] = null;
                $user["expired_otp"] = null;
                $userModel->save($user);
                throw new Exception("invalid otp please login again");
            }

            $key = getenv("JWT_SECRET_KEY");
            $uuid = service('uuid');
            $uuid4 = $uuid->uuid4();
            $string_uuid = $uuid4->toString();
            $payload = [
                'sub' => $user["id"],
                'iss' => 'pm_kukar',
                'iat' => time(),
                'exp' => time() + 3600,
                'aud' => '',
                'jti' => $string_uuid,
                'role' => $user["role"]
            ];

            $token = JWT::encode($payload, $key, "HS256");

            $user["token"] = $token;

            $userModel->save($user);

            unset($user["password"]);

            log_message("info", "end method verifyOtp on AuthController");
            return Response::apiResponse("success verify", $user);
        } catch (\Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
