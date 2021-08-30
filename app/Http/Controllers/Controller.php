<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $successStatusCode = 200;
    
    /**
     * @author : Shubham Dayma
     * @param  : Success message, HTTP status code and data in case of any
     * @return : Json encoded response to caller
    */
    public function sendResponse($message, $status_code, $data = '') {
        $response = [
                'status' => true,
                'status_code' => $status_code,
                'message' => $message,
                'data' => $data,
            ];

        return response()->json($response, $status_code);
    }

    /**
     * @author : Shubham Dayma
     * @param  : Error message, HTTP status code and data in case of any
     * @return : Json encoded response to caller
    */
    public function sendError($error, $status_code = 404, $data = '') {
        $response = [
            'status' => false,
            'status_code' => $status_code,
            'message' => $error,
            'data' => $data
        ];

        return response()->json($response, $status_code);    
    }
}
