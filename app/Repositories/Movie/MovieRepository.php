<?php

namespace App\Repositories\Movie;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function create(array $parameters): Movie
    {
        $this->validate($parameters);

        $movie = Movie::create($this->parseParameters($parameters));

        $this->attachGenres($movie, Arr::get($parameters, 'genre_ids', []));

        $this->syncActors($movie, $parameters['actors']);

        $this->syncDirectors($movie, $parameters['directors']);

        return $movie;
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function update($movie, array $parameters): Movie
    {
        $this->validate($parameters);

        $movie->update($this->parseParameters($parameters));

        $this->attachGenres($movie, $parameters['genre_ids']);

        $this->syncActors($movie, $parameters['actors']);

        $this->syncDirectors($movie, $parameters['directors']);

        return $movie;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function delete($movie): ?bool
    {
        return $movie->delete();
    }

    /**
     * @inheritDoc
     */
    protected function parseParameters(array $parameters): array
    {
        $parameters['slug'] ??= str_slug($parameters['title'] . ' ' . $parameters['year'], '-');
        if (isset($parameters['released']) && !$parameters['released'] instanceof Carbon) {
            $parameters['released'] = Carbon::createFromFormat('j M Y', $parameters['released']);
        }

        return $parameters;
    }

    /**
     * @inheritDoc
     */
    protected function validationRules(array $parameters = []): array
    {
        $year = $parameters['year'];

        return [
            'title' => [
                'required', 'string', 'max:255', Rule::unique('movies')->where(function ($query) use ($year) {
                    return $query->where('year', $year);
                })
            ],
            'year' => 'required|digits:4',
            'released' => 'present|nullable|date',
            'runtime' => 'present|nullable|integer',
            'plot' => 'present|nullable|string|min:5',
            'review' => 'nullable|string|min:5',
            'poster' => 'present|nullable|string|max:500',
            'rotten_tomatoes_rating' => 'present|nullable|string',
            'metacritic_rating' => 'present|nullable|string',
            'slug' => 'nullable|string|max:255|unique:movies,slug',
            'imdb_id' => 'present|nullable|string',
            'genre_ids' => 'present|nullable|array',
            'genre_ids.*' => 'present|nullable|int|exists:genres,id',
            'actors' => 'present|array',
            'actors.*' => 'present|nullable|string',
            'directors' => 'present|array',
            'directors.*' => 'present|nullable|string',
        ];
    }

    /**
     * @param  Movie  $movie
     * @param  array  $genreIds
     */
    private function attachGenres(Movie $movie, array $genreIds)
    {
        if (filled($genreIds)) {
            $movie->genres()->attach($genreIds);
        }
    }

    /**
     * @param  Movie  $movie
     * @param  array  $actorNames
     */
    private function syncActors(Movie $movie, array $actorNames)
    {
        $actorIds = [];
        if (filled($actorNames)) {
            foreach ($actorNames as $actorName) {
                $actor = Actor::firstOrCreate(
                    ['full_name' => $actorName],
                    ['slug' => str_slug($actorName, '-')]
                );
                array_push($actorIds, $actor->id);
            }

            $movie->actors()->sync($actorIds);
        }
    }

    /**
     * @param  Movie  $movie
     * @param  array  $directorNames
     */
    private function syncDirectors(Movie $movie, array $directorNames)
    {
        $directorIds = [];
        if (filled($directorNames)) {
            foreach ($directorNames as $directorName) {
                $actor = Director::firstOrCreate(
                    ['full_name' => $directorName],
                    ['slug' => str_slug($directorName, '-')]
                );
                array_push($directorIds, $actor->id);
            }
            $movie->directors()->sync($directorIds);
        }
    }
}