<?php
namespace App\Helpers;

class ResponseHelper
{
    public static function formatResponse($statusCode, $data = null, $message = '', $success = true)
    {
        return response()->json([
            'status_code' => $statusCode,
            'data' => $data,
            'message' => $message,
            'success' => $success
        ]);
    }
}
