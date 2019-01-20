<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieCreateUpdateRequest extends FormRequest
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

    public function rules()
    {
        return $this->requestMovieRules();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function requestMovieRules()
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('movies')->where(function ($query){return $query->where('year', request('year'));})  ],
            'year' => 'nullable|integer',
            'released' => 'nullable|date',
            'runtime' => 'nullable|integer',
            'plot' => 'nullable|string|min:5',
            'review' => 'nullable|string|min:5',
            'poster' => 'nullable|string|max:500',
            'internet_movie_database_rating' => '',
            'rotten_tomatoes_rating' => '',
            'metacritic_rating' => '',
            'slug' => 'required|string|max:255|unique:movies,slug',
        ];
    }
}
