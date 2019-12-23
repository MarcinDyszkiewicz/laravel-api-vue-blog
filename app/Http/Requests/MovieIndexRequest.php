<?php

namespace App\Http\Requests;

use App\MyFormRequest\Http\MyFormRequest;

class MovieIndexRequest extends MyFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'title' => 'required|string|max:100',
//            'year' => 'present|nullable|max:4'
        ];
    }

    /**
     * @return array
     */
    public function allowedParams(): array
    {
        return [
            'order_by',
            'order_dir'
        ];
    }
}
