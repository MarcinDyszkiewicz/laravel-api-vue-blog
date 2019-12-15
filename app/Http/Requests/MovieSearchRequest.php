<?php

namespace App\Http\Requests;

use App\MyFormRequest\Http\MyFormRequest;

class MovieSearchRequest extends MyFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'year' => 'present|nullable|max:4'
        ];
    }

    /**
     * Returns allowed form request params
     *
     * @return array
     */
    public function allowedParams()
    {
        return [
            'title',
            'year'
        ];
    }
}
