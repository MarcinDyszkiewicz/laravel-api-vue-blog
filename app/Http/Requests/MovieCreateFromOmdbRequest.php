<?php

namespace App\Http\Requests;

use App\MyFormRequest\Http\MyFormRequest;

class MovieCreateFromOmdbRequest extends MyFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'omdb_id' => 'required|string'
        ];
    }
}
