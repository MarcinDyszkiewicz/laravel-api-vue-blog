<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateUpdateRequest extends FormRequest
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
        //@@todo meta max check
        return [
            'movie_id' => 'nullable|integer',
            'title' => 'required|string|min:5|max:600',
            'body' => 'required|string|min:5',
            'image' => 'required|string',
            'meta_title' => 'nullable|string|min:10|max:255',
            'meta_description' => 'nullable|string|min:50|max:255',
            'summary' => 'nullable|string|min:5|max:500',
            'slug' => 'nullable|string|min:5|max:600',
            'categoryIds' => 'nullable|array',
            'categoryIds.*' => 'nullable|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|min:3|max:255',
        ];
    }
}
