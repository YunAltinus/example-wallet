<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param string $message
     * @param integer $code
     * @param array $messages
     * @return \Illuminate\Http\JsonResponse
     */
    function sendError($message, $code = 404, $messages = [])
    {
        $response["message"] = $message;

        !empty($messages) ? $response["errors"] = $messages : null;
        return response()->json($response, $code);
    }
}
