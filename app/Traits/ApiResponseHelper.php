<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.06.11 at 12:09:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */
namespace App\Traits;


trait ApiResponseHelper {
  

    public function errorResponse($message = null, $code=401)
    {
        return response()->json([
            'status'=> false,
            'message' => $message,
            'data' => null
        ], $code);
    }

  


    public function success() {
        return 200;
    }
    public function failed() {
        return 401;
    }
    public function invalid() {
        return 400;
    }
    public function validation() {
        return 422;
        // return 202;
    }
}