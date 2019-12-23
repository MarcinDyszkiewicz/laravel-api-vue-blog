<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidationExceptionResponse extends JsonResponse
{

    /**
     * ValidationExceptionResponse constructor.
     * @param  ValidationException  $e
     */
    public function __construct(ValidationException $e)
    {
        $data = [
            'data' => null,
            'message' => $e->getMessage(),
            'errors' => $e->errors(),
            'success' => false
        ];
        $status = self::HTTP_UNPROCESSABLE_ENTITY;
        $headers = [];

        parent::__construct($data, $status, $headers);
    }
}