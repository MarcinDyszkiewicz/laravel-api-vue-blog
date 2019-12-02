<?php

namespace App\Omdb;

use App\Models\Movie;
use App\Omdb\Models\OmdbMovie;

class Omdb
{
    /**
     * @var OmdbApi
     */
    private OmdbApi $omdbApi;

    public function __construct(OmdbApi $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }

    public function find(string $title, string $year): OmdbMovie
    {
        $response = $this->omdbApi->findInOmdb($title, $year);
        return new OmdbMovie(json_decode($response, true));
    }

    public function createMovieFromOmdb(OmdbMovie $omdbMovie)
    {
        $movie = Movie::create($omdbMovie);
    }
}
