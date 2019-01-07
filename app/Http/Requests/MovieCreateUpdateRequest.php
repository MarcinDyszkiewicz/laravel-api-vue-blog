<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'requestMovie.title' => 'required',
            'requestMovie.year' => '',
            'requestMovie. released' => '',
            'runtime' => '',
            'plot' => '',
            'review' => '',
            'poster' => '',
            'internet_movie_database_rating' => '',
            'rotten_tomatoes_rating' => '',
            'metacritic_rating' => '',
            'imdb_rating' => '',
            'slug' => '',
        ];
    }
}
