<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ResponseFormat
{

    public function responseSuccess($data = null, $message = "SUCCESS", $code = 200)
    {
        return response()->json([
            'status'     => 'success',
            'message'     => $message,
            'data'         => $data,
            'code'      => $code
        ], $code);
    }

    public function responseFail($data = null, $message = "FAIL", $errors = null, $code = 400)
    {
        Log::debug('responseFail: ' . json_encode($data));
        return response()->json([
            'status'     => 'fail',
            'message'     => $message,
            'errors'    => $errors,
            'data'         => $data,
            'code'      => $code
        ], $code);
    }
}
