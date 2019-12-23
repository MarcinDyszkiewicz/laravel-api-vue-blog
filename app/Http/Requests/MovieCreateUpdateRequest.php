<?php

namespace App\Http\Requests;

use App\MyFormRequest\Http\MyFormRequest;
use Illuminate\Validation\Rule;

class MovieCreateUpdateRequest extends MyFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required', 'string', 'max:255', Rule::unique('movies')->where(function ($query) {
                    return $query->where('year', request('year'));
                })
            ],
            'year' => 'required|digits:4',
            'released' => 'present|nullable|date',
            'runtime' => 'present|nullable|integer',
            'plot' => 'present|nullable|string|min:5',
            'review' => 'present|nullable|string|min:5',
            'poster' => 'present|nullable|string|max:500',
            'rotten_tomatoes_rating' => 'present|nullable|string',
            'metacritic_rating' => 'present|nullable|string',
            'slug' => 'present|nullable|string|max:255|unique:movies,slug',
            'imdb_id' => 'present|nullable|string',
            'genre_ids' => 'present|array',
            'genre_ids.*' => 'present|nullable|int|exists:genres,id',
            'actors' => 'present|array',
            'actors.*' => 'present|nullable|string',
            'directors' => 'present|array',
            'directors.*' => 'present|nullable|string',
        ];
    }

    public function allowedParams(): array
    {
        return [
            'title',
            'year',
            'released',
            'runtime',
            'plot',
            'review',
            'poster',
            'internet_movie_database_rating',
            'rotten_tomatoes_rating',
            'metacritic_rating',
            'slug',
            'imdb_id',
            'genre_ids',
            'actors',
            'directors'
        ];
    }
}
