<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExceptionResponse extends JsonResponse
{
    /**
     * ValidationExceptionResponse constructor.
     * @param  \Exception  $e
     */
    public function __construct(\Exception $e)
    {
        $message = $e->getMessage();
        $code = $e->getCode();
        if ($code >= 500 && $code < 600) {
            $message = 'Server Error';
        }
        if ($code == 0) {
            $code = self::HTTP_BAD_REQUEST;
        }

        $data = [
            'data' => null,
            'message' => $message,
            'success' => false
        ];
        $status = $code;
        $headers = [];

        parent::__construct($data, $status, $headers);
    }
}