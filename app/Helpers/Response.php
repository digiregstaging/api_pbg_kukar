<?php

namespace App\Helpers;

class Response
{
    public static function apiResponse($message = "", $data = [], $httpCode = 200)
    {
        $response = service('response');

        $jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        $response_data = [
            "message" => $message,
            "data" => $data
        ];
        $content = json_encode($response_data, $jsonOptions);

        return $response->setStatusCode($httpCode)
            ->setContentType('application/json')
            ->setBody($content);
    }
}
