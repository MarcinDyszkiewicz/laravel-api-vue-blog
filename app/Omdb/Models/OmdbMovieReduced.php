<?php

namespace App\Omdb\Models;

use Illuminate\Support\Arr;

class OmdbMovieReduced
{
    public ?int $id;
    public string $title;
    public int $year;
    public string $poster;
    public ?string $slug;
    public string $imdb_id;

    public function __construct(array $data)
    {
        $this->id = null;
        $this->imdb_id = Arr::get($data, 'imdbID');
        $this->title = Arr::get($data, 'Title');
        $this->year = Arr::get($data, 'Year');
        $this->poster = Arr::get($data, 'Poster');
        $this->slug = null;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
