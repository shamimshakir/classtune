<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendSuccess(
        string $message = "Success",
        mixed $data = [], 
        int $code = 200
    ): JsonResponse {
    	$response = [
            'status' => true,
            'message' => $message,
            'data'    => $data,
        ];
        return response()->json($response, $code);
    }

    public function sendFailed(
        string $message = 'Failed', 
        mixed $errors = [], 
        int $code = 400
    ): JsonResponse {
    	$response = [
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ]; 
        return response()->json($response, $code);
    }
}
