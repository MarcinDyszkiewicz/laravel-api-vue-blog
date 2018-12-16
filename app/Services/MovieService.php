<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Movie;
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
                'type' => 'movie',
                't' => $title,
                'y' => $year
            ]
        ])->getBody();

        return $movie;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createMovie($data)
    {
        $omdbMovieData = array_get($data, 'omdbMovie');
        $requestMovieData = array_get($data, 'requestMovie');
        $title = array_get($requestMovieData, 'title')?? $omdbMovieData['Title'];
        $year = array_get($requestMovieData, 'year')?? $omdbMovieData['Year'];
        $actorsNames = array_get($requestMovieData, 'Actors')?? $omdbMovieData['Actors'];

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

        $actorsNamesArray = explode(', ', $actorsNames);
        $actorIds = [];
        foreach ($actorsNamesArray as $actorName) {
            $actor = Actor::where('full_name', $actorName)->first();
            if (!$actor) {
                $actor = Actor::create(['full_name' => $actorName]);
            }
            array_push($actorIds, $actor->id);
        }
        $movie->actors()->attach($actorIds);

        return $movie;
    }

    /**
     * Gets single movie from database if exists, if not gets movie data from omdb
     *
     * @param $data
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function showSingleMovie($data)
    {
        $title = array_get($data, 'title');
        $year = array_get($data, 'year');
        $slug = str_slug($title. ' '. $year);
        $movie = Movie::where('slug', $slug)->first();

        if (!$movie) {
            $GuzzleClient = new Client();
            $movie = $GuzzleClient->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
                'query' => [
                    'apikey' => 'c9d3739b',
                    'type' => 'movie',
                    't' => $title,
                    'y' => $year
                ]
            ])->getBody();
        }

        return $movie;
    }

    public function updateMovie($requestMovieData, Movie $movie)
    {
        $title = array_get($requestMovieData, 'title');
        $year = array_get($requestMovieData, 'year');
        $movie->update([
            'title' => $title,
            'year' => $year,
            'released' => array_get($requestMovieData, 'released'),
            'runtime' => array_get($requestMovieData, 'runtime'),
            'plot' => array_get($requestMovieData, 'plot'),
            'review' => array_get($requestMovieData, 'review'),
            'poster' => array_get($requestMovieData, 'poster'),
            'slug' => str_slug($title.' '.$year, '-'),
        ]);

        return $movie;
    }
}