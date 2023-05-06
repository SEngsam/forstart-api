<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    //
        //
        public function sendResponse($result, $message)
        {
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ];
    
    
            return response()->json($response, 200);
        }
    
    
        /**
         * return error response.
         *
         * @return \Illuminate\Http\Response
         */
        public function sendError($error, $errorMessages = [], $code = 404)
        {
            $response = [
                'success' => false,
                'message' => $error,
            ];
    
    
            if (!empty($errorMessages)) {
                $response['data'] = $errorMessages;
            }
    
    
            return response()->json($response, $code);
        }
    
        function exMessage($e)
        {
    
            if (env('APP_ENV')  == 'production') {
                return __('client.form.some_errors');
            } else {
                return $e->getMessage();
    
            }
        }
}
