<?php

namespace App\Omdb\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class OmdbMovie
{
    public string $title;
    public string $year;
    public string $rated;
    public \DateTime $released;
    public string $runtime;
    public string $genre;
    public array $directors;
    public array $writers;
    public array $actors;
    public string $plot;
    public array $languages;
    public array $countrys;
    public string $awards;
    public string $poster;
    public array $ratings;
    public string $metascore;
    public string $imdbRating;
    public string $imdbVotes;
    public string $imdbId;
    public string $type;
    public \DateTime $dvd;
    public string $boxOffice;
    public string $production;

    public function __construct(array $data)
    {
        $this->title = Arr::get($data, 'Title');
        $this->year = Arr::get($data, 'Year');
        $this->rated = Arr::get($data, 'Rated');
        $this->released = $this->parseToCarbonDateTime(Arr::get($data, 'Released'));
        $this->runtime = (int) Arr::get($data, 'Runtime');
        $this->genre = Arr::get($data, 'Genre');
        $this->directors = $this->parseToArray(Arr::get($data, 'Director'));
        $this->writers = $this->parseToArray(Arr::get($data, 'Writer'));
        $this->actors = $this->parseToArray(Arr::get($data, 'Actors'));
        $this->plot = Arr::get($data, 'Plot');
        $this->languages = $this->parseToArray(Arr::get($data, 'Language'));
        $this->countrys = $this->parseToArray(Arr::get($data, 'Country'));
        $this->awards = Arr::get($data, 'Awards');
        $this->poster = Arr::get($data, 'Poster');
        $this->ratings = Arr::get($data, 'Ratings');
        $this->metascore = Arr::get($data, 'Metascore');
        $this->imdbRating = Arr::get($data, 'imdbRating');
        $this->imdbVotes = Arr::get($data, 'imdbVotes');
        $this->imdbId = Arr::get($data, 'imdbID');
        $this->type = Arr::get($data, 'Type');
        $this->dvd = $this->parseToCarbonDateTime(Arr::get($data, 'DVD'));
        $this->boxOffice = Arr::get($data, 'BoxOffice');
        $this->production = Arr::get($data, 'Production');
    }


    /**
     * @param string|null $string
     * @return array
     */
    private function parseToArray(?string $string): array
    {
        return explode(', ', $string);
    }

    /**
     * @param string|null $string
     * @return Carbon
     */
    private function parseToCarbonDateTime(?string $string): Carbon
    {
        return Carbon::createFromFormat('j M Y', $string);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
