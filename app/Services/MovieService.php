<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use App\Models\Rating;
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
        $data = ['requestMovie' => $data];
        $omdbMovieData = array_get($data, 'omdbMovie');
        $requestMovieData = array_get($data, 'requestMovie');
        $title = array_get($requestMovieData, 'title')?? $omdbMovieData['Title'];
        $year = array_get($requestMovieData, 'year')?? $omdbMovieData['Year'];
        $genresIds = array_get($requestMovieData, 'genresIds');
        $actorsNames = array_get($requestMovieData, 'actors')?? $omdbMovieData['Actors'];
        $directorsNames = array_get($requestMovieData, 'director')?? $omdbMovieData['Director'];

        $movie = Movie::create([
            'title' => $title,
            'year' => $year,
            'released' => Carbon::createFromFormat('j M Y', array_get($requestMovieData, 'released'))?? Carbon::createFromFormat('j M Y', $omdbMovieData['Released']),
            'runtime' => array_get($requestMovieData, 'runtime')?? substr($omdbMovieData['Runtime'], 0, strpos($omdbMovieData['Runtime'], ' min')),
            'plot' => array_get($requestMovieData, 'plot')?? $omdbMovieData['Plot'],
            'review' => array_get($requestMovieData, 'review'),
            'poster' => array_get($requestMovieData, 'poster')?? $omdbMovieData['Poster'],
            'internet_movie_database_rating' => array_get($omdbMovieData, 'Ratings.0.Value'),
            'rotten_tomatoes_rating' => array_get($omdbMovieData, 'Ratings.1.Value'),
            'metacritic_rating' => array_get($omdbMovieData, 'Ratings.2.Value'),
            'slug' => array_get($requestMovieData, 'slug')?? str_slug($title.' '.$year, '-'),
        ]);

        //Genres
        if ($genresIds) {
            $movie->genres()->sync(array_wrap($genresIds));
        }

        //Actors
        if ($actorsNames) {
            $actorsNamesArray = explode(', ', $actorsNames);
            $actorIds = [];
            foreach ($actorsNamesArray as $actorName) {
                $actor = Actor::where('full_name', $actorName)->first();
                if (!$actor) {
                    $actor = Actor::create(['full_name' => $actorName, 'slug' => str_slug($actorName, '-')]);
                }
                array_push($actorIds, $actor->id);
            }
            $movie->actors()->attach($actorIds);
        }


        //Directors
        if ($directorsNames) {
            $directorsNamesArray = explode(', ', $directorsNames);
            $directorIds = [];
            foreach (array_wrap($directorsNamesArray) as $directorName) {
                $director = Director::where('full_name', $directorName)->first();
                if (!$director) {
                    $director = Director::create(['full_name' => $directorName]);
                }
                array_push($directorIds, $director->id);
            }
            $movie->directors()->attach($directorIds);
        }


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

    /**
     * @param $requestMovieData
     * @param Movie $movie
     * @return Movie
     */
    public function updateMovie($requestMovieData, Movie $movie)
    {
        $title = array_get($requestMovieData, 'title');
        $year = array_get($requestMovieData, 'year');
        $genresIds = array_get($requestMovieData, 'genresIds');
        $actorsNames = array_get($requestMovieData, 'actors');
        $directorsNames = array_get($requestMovieData, 'director');

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

        //Genres
        if ($genresIds) {
            $movie->genres()->sync(array_wrap($genresIds));
        }

        //Actors
        $actorsNamesArray = explode(', ', $actorsNames);
        $actorIds = [];
        foreach ($actorsNamesArray as $actorName) {
            $actor = Actor::where('full_name', $actorName)->first();
            if (!$actor) {
                $actor = Actor::create(['full_name' => $actorName]);
            }
            array_push($actorIds, $actor->id);
        }
        $movie->actors()->sync($actorIds);

        //Directors
        $directorsNamesArray = explode(', ', $directorsNames);
        $directorIds = [];
        foreach (array_wrap($directorsNamesArray) as $directorName) {
            $director = Director::where('full_name', $directorName)->first();
            if (!$director) {
                $director = Director::create(['full_name' => $directorName]);
            }
            array_push($directorIds, $director->id);
        }
        $movie->directors()->sync($directorIds);

        return $movie;
    }

    /**
     * @param $data
     * @param $userId
     * @param Movie $movie
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rateMovie($data, $userId, Movie $movie)
    {
        $rate = array_get($data, 'rate');
        $rating = null;
        abort_if ($movie->ratings()->where('user_id', $userId)->exists(), 400, 'You have already rated this movie');
//@@ todo lepiej throw niż abort_if
        $rating = $movie->ratings()->create([
            'user_id' => $userId,
            'rate' => $rate
        ]);


        return $rating;
    }

    /**
     * @param Movie $movie
     * @return float
     */
    public function calculateMovieRating(Movie $movie)
    {
        $ratings = $movie->ratings()->avg('rate');

        return round($ratings, 2);
    }
}