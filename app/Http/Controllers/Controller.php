<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function successResponse($result, $message)
    {
        $response = [
            'success' => true,
            'status_code' => Response::HTTP_OK,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function errorResponse($errorMessages = [], $error="", $code = 200)
    {
        $response = [
            'success' => false,
            'status_code' => $code,
            'message' => $errorMessages,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = null;
        }
        return response()->json($response, $code);
    }
}
