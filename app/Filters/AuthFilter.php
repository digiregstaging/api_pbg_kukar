<?php

namespace App\Filters;

use App\Helpers\Response;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message("info", "start AuthFilter");
        $key = getenv('JWT_SECRET_KEY');
        $header = $request->header("Authorization");
        $token = null;

        // extract the token from the header
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        } else {
            return Response::apiResponse("token required", null, 401);
        }

        try {
            $credentials = JWT::decode($token, new Key($key, 'HS256'));
        } catch (ExpiredException $e) {
            return Response::apiResponse("token expired", $e->getMessage(), 401);
        } catch (SignatureInvalidException $e) {
            return Response::apiResponse("signature key invalid", $e->getMessage(), 401);
        } catch (UnexpectedValueException $e) {
            return Response::apiResponse("invalid token format", $e->getMessage(), 401);
        }

        $userModel = new User();
        $user = $userModel->where("id", $credentials->sub)
            ->where("token", $token)
            ->first();


        if (!$user) {
            return Response::apiResponse("user unauthorize", null, 401);
        }

        unset($user["password"]);
        $request->user = $user;
        log_message("info", "end AuthFilter");
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
