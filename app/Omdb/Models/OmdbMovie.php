<?php

namespace App\Omdb\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class OmdbMovie
{
    public string $title;
    public int $year;
    public string $rated;
    public ?\DateTime $released;
    public int $runtime;
    public array $genres;
    public array $directors;
    public array $writers;
    public array $actors;
    public string $plot;
    public array $languages;
    public array $countries;
    public string $awards;
    public string $poster;
    public array $ratings;
    public string $metacritic_rating;
    public string $imdb_rating;
    private string $rotten_tomatoes_rating;
    public string $imdbVotes;
    public string $imdb_id;
    public string $type;
    public ?\DateTime $dvd;
    public string $boxOffice;
    public string $production;

    public function __construct(array $data)
    {
        //@todo metacritic - int i nie wieksze niz 100. internet_movie_database_rating - wywalić.
        // @todo imdb_rating - double max 10.0
        // @todo rotten_tomatoes_rating - string
        $this->title = Arr::get($data, 'Title');
        $this->year = Arr::get($data, 'Year');
        $this->rated = Arr::get($data, 'Rated');
        $this->released = $this->parseToCarbonDateTime(Arr::get($data, 'Released'));
        $this->runtime = (int) Arr::get($data, 'Runtime');
        $this->genres = $this->parseToArray(Arr::get($data, 'Genre'));
        $this->directors = $this->parseToArray(Arr::get($data, 'Director'));
        $this->writers = $this->parseToArray(Arr::get($data, 'Writer'));
        $this->actors = $this->parseToArray(Arr::get($data, 'Actors'));
        $this->plot = Arr::get($data, 'Plot');
        $this->languages = $this->parseToArray(Arr::get($data, 'Language'));
        $this->countries = $this->parseToArray(Arr::get($data, 'Country'));
        $this->awards = Arr::get($data, 'Awards');
        $this->poster = Arr::get($data, 'Poster');
        $this->ratings = Arr::get($data, 'Ratings');
        $this->metacritic_rating = Arr::get($data, 'Metascore');
        $this->imdb_rating = Arr::get($data, 'imdbRating');
        $this->rotten_tomatoes_rating = Arr::get($data, 'imdbRating');
        $this->imdbVotes = Arr::get($data, 'imdbVotes');
        $this->imdb_id = Arr::get($data, 'imdbID');
        $this->type = Arr::get($data, 'Type');
        $this->dvd = $this->parseToCarbonDateTime(Arr::get($data, 'DVD'));
        $this->boxOffice = Arr::get($data, 'BoxOffice');
        $this->production = Arr::get($data, 'Production');
    }


    /**
     * @param  string|null  $string
     * @return array
     */
    private function parseToArray(?string $string): array
    {
        return explode(', ', $string);
    }

    /**
     * @param  string|null  $string
     * @return Carbon
     */
    private function parseToCarbonDateTime(?string $string): ?Carbon
    {
        if (!(int) $string) {
            return null;
        }
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
