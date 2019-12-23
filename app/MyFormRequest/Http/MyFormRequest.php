<?php

namespace App\MyFormRequest\Http;

use App\Exceptions\MyValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class MyFormRequest extends FormRequest
{
    /**
     * @return array
     */
    public function allowedParams(): array
    {
        return array_keys($this->container->call([$this, 'rules']));
    }

    /**
     * @return array
     */
    public function onlyAllowedParams(): array
    {
        return $this->only($this->allowedParams());
    }

        /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new MyValidationException($validator, $this->createResponse()))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function createResponse()
    {
        return response()->json(
            [
                'data' => null,
                'message' => 'The given data was invalid.',
                'errors' => $this->validator->errors()->messages(),
                'success' => false
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}