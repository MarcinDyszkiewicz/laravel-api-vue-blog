<?php

namespace App\Omdb\Models;

use Illuminate\Support\Arr;

class OmdbMovieReduced
{
    public string $imdbId;
    public string $title;
    public int $year;
    public string $poster;

    public function __construct(array $data)
    {
        $this->imdbId = Arr::get($data, 'imdbID');
        $this->title = Arr::get($data, 'Title');
        $this->year = Arr::get($data, 'Year');
        $this->poster = Arr::get($data, 'Poster');
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
