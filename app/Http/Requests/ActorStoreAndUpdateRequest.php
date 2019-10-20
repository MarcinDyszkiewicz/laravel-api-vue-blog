<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActorStoreAndUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string|unique:actors,full_name|regex:/(?=^.{5,50}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/',
            'poster' => 'nullable|string|url',
            'movie_ids' => 'array',
            'movie_ids.*' => 'nullable|integer|exists:movies,id'
        ];
    }

    /**
     * @return array
     */
    public static function allowedParams()
    {
        return [
            'full_name',
            'poster',
            'movie_ids'
        ];
    }
}
