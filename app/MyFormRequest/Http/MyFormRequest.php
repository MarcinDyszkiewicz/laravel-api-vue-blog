<?php

namespace App\MyFormRequest\Http;

use Illuminate\Foundation\Http\FormRequest;

class MyFormRequest extends FormRequest
{
    /**
     * @return array
     */
    public function allowedParams()
    {
        if (!method_exists($this, 'allowedParams')) {
            return array_keys($this->container->call([$this, 'rules']));
        }
        return [];
    }

    /**
     * @return array
     */
    public function onlyAllowedParams()
    {
        return $this->only($this->allowedParams());
    }
}