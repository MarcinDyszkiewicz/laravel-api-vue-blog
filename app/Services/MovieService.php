<?php

namespace App\Services;

use App\Movie;
use Carbon\Carbon;
use GuzzleHttp\Client;

class MovieService
{
    /**
     * @param $data
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findInOmdb($data)
    {
        $title = array_get($data, 'title');
        $year = array_get($data, 'year');

        $GuzzleClient = new Client();
        $movie = $GuzzleClient->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
            'query' => [
                'apikey' => 'c9d3739b',
                't' => $title,
                'y' => $year
            ]
        ])->getBody();

        return $movie;
    }

    public function createMovie($data)
    {
        $omdbMovieData = array_get($data, 'omdbMovie');
        $requestMovieData = array_get($data, 'requestMovie');
        $title = array_get($requestMovieData, 'title')?? $omdbMovieData['Title'];
        $year = array_get($requestMovieData, 'year')?? $omdbMovieData['Year'];

        $movie = Movie::create([
            'title' => $title,
            'year' => $year,
            'released' => array_get($requestMovieData, 'released')?? Carbon::createFromFormat('j M Y', $omdbMovieData['Released']),
            'runtime' => array_get($requestMovieData, 'runtime')?? substr($omdbMovieData['Runtime'], 0, strpos($omdbMovieData['Runtime'], ' min')),
            'plot' => array_get($requestMovieData, 'plot')?? $omdbMovieData['Plot'],
            'review' => array_get($requestMovieData, 'review'),
            'poster' => array_get($requestMovieData, 'poster')?? $omdbMovieData['Poster'],
            'internet_movie_database_rating' => array_get($omdbMovieData, 'Ratings.0.Value'),
            'rotten_tomatoes_rating' => array_get($omdbMovieData, 'Ratings.1.Value'),
            'metacritic_rating' => array_get($omdbMovieData, 'Ratings.2.Value'),
            'imdb_rating' => $omdbMovieData['imdbRating'],
            'slug' => str_slug($title.' '.$year, '-'),
        ]);

        return $movie;
    }
}