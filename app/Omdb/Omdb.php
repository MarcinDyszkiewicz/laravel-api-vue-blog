<?php

namespace App\Omdb;

use App\Models\Movie;
use App\Omdb\Models\OmdbMovie;
use App\Omdb\Models\OmdbMovieReduced;

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

    public function findByTitle(string $title, ?string $year): OmdbMovie
    {
        $response = $this->omdbApi->findInOmdbByTitle($title, $year);

        return new OmdbMovie(json_decode($response, true));
    }

    /**
     * @param  string  $omdbId
     * @return OmdbMovie
     */
    public function findById(string $omdbId): OmdbMovie
    {
        $response = $this->omdbApi->findInOmdbById($omdbId);

        return new OmdbMovie($response);
    }

    /**
     * @param array $data
     * @return array
     */
    public function search(array $data): array
    {
        $title = $data['title'];
        $year = $data['year'];
        $responseMovies = $this->omdbApi->searchInOmdb($title, $year);
        $omdbMovies = [];
        foreach ($responseMovies as $responseMovie) {
            $omdbMovies[] = (new OmdbMovieReduced($responseMovie))->toArray();
        }

        return $omdbMovies;
    }

    public function createMovieFromOmdb(OmdbMovie $omdbMovie)
    {
        $movie = Movie::create($omdbMovie);
    }
}
