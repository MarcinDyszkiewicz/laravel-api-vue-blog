<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use App\Models\Rating;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class MovieService
{
    /**
     * @param $data
     * @return \Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findInOmdb($data)
    {
        $title = Arr::get($data, 'title');
        $year = Arr::get($data, 'year');

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
        dd($data);
        $omdbMovieData = Arr::get($data, 'omdbMovie');
        $requestMovieData = Arr::get($data, 'requestMovie');
        $title = Arr::get($requestMovieData, 'title') ?? $omdbMovieData['Title'];
        $year = Arr::get($requestMovieData, 'year') ?? $omdbMovieData['Year'];
        $genresIds = Arr::get($requestMovieData, 'genresIds');
        $actorsNames = Arr::get($requestMovieData, 'actors') ?? $omdbMovieData['Actors'];
        $directorsNames = Arr::get($requestMovieData, 'director') ?? $omdbMovieData['Director'];

        $movie = Movie::create([
            'title' => $title,
            'year' => $year,
            'released' => Carbon::createFromFormat('j M Y', Arr::get($requestMovieData, 'released')) ?? Carbon::createFromFormat('j M Y', $omdbMovieData['Released']),
            'runtime' => Arr::get($requestMovieData, 'runtime') ?? substr($omdbMovieData['Runtime'], 0, strpos($omdbMovieData['Runtime'], ' min')),
            'plot' => Arr::get($requestMovieData, 'plot') ?? $omdbMovieData['Plot'],
            'review' => Arr::get($requestMovieData, 'review'),
            'poster' => Arr::get($requestMovieData, 'poster') ?? $omdbMovieData['Poster'],
            'internet_movie_database_rating' => Arr::get($omdbMovieData, 'Ratings.0.Value'),
            'rotten_tomatoes_rating' => Arr::get($omdbMovieData, 'Ratings.1.Value'),
            'metacritic_rating' => Arr::get($omdbMovieData, 'Ratings.2.Value'),
            'slug' => Arr::get($requestMovieData, 'slug') ?? str_slug($title . ' ' . $year, '-'),
        ]);

        //Genres
        if ($genresIds) {
            $movie->genres()->sync(array_wrap($genresIds));
        }

        //Actors
        if ($actorsNames) {
//            $actorsNamesArray = explode(', ', $actorsNames);
            $actorIds = [];
            foreach ($actorsNames as $actorName) {
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
//            $directorsNamesArray = explode(', ', $directorsNames);
            $directorIds = [];
            foreach (array_wrap($directorsNames) as $directorName) {
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
        $title = Arr::get($data, 'title');
        $year = Arr::get($data, 'year');
        $slug = str_slug($title . ' ' . $year);
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
        $title = Arr::get($requestMovieData, 'title');
        $year = Arr::get($requestMovieData, 'year');
        $genresIds = Arr::get($requestMovieData, 'genresIds');
        $actorsNames = Arr::get($requestMovieData, 'actors');
        $directorsNames = Arr::get($requestMovieData, 'director');

        $movie->update([
            'title' => $title,
            'year' => $year,
            'released' => Arr::get($requestMovieData, 'released'),
            'runtime' => Arr::get($requestMovieData, 'runtime'),
            'plot' => Arr::get($requestMovieData, 'plot'),
            'review' => Arr::get($requestMovieData, 'review'),
            'poster' => Arr::get($requestMovieData, 'poster'),
            'slug' => str_slug($title . ' ' . $year, '-'),
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
        $rate = Arr::get($data, 'rate');
        $rating = null;
        abort_if($movie->ratings()->where('user_id', $userId)->exists(), 400, 'You have already rated this movie');
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
