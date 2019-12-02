<?php

namespace App\Omdb\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class OmdbMovie
{
    /**
     * @var array
     */
    private array $data;
    private string $title;
    private string $year;
    private string $rated;
    private \DateTime $released;
    private string $runtime;
    private string $genre;
    private array $directors;
    private array $writers;
    private array $actors;
    private string $plot;
    private array $languages;
    private array $countrys;
    private string $awards;
    private string $poster;
    private string $ratings;
    private string $metascore;
    private string $imdbRating;
    private string $imdbVotes;
    private string $imdbId;
    private string $type;
    private \DateTime $dvd;
    private string $boxOffice;
    private string $production;

    public function __construct(array $data)
    {
        $this->data = $data;
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
}
