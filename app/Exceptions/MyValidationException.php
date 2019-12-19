<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class MyValidationException extends ValidationException
{
    /**
     * @var bool
     */
    public bool $success;

    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);

        $this->success = false;
    }
}