<?php

namespace App\Http\Responses;

class MyJsonResponse extends \Illuminate\Http\JsonResponse
{
    public function __construct(
        $data = null,
        $success = true,
        $message = 'Ok',
        $status = 200,
        $headers = [],
        $options = 0
    ) {
        $allData = ['data' => $data, 'success' => $success, 'message' => $message];
        parent::__construct($allData, $status, $headers, $options);
    }
}