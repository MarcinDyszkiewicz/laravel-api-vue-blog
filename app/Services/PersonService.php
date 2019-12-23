<?php

namespace App\Services;

use App\Factories\Person\PersonFactory;
use App\Models\Actor;
use App\Models\ActorMovie;
use App\Models\Director;
use App\Models\Movie;
use App\Repositories\Person\PersonRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class PersonService
{
    /**
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createActor(array $data): Model
    {
//        try {
            $personFactory = new PersonRepository(new Actor());

            return $personFactory->create($data);
//        } catch (ValidationException $exception) {
//            abort(400, $exception->getMessage());
//        }
    }

    /**
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createDirector(array $data): Model
    {
        $personFactory = new PersonRepository(new Director(), $data);

        return $personFactory->create();
    }

    /**
     * @param Model $model
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePerson(Model $model, array $data): Model
    {
        $personFactory = new PersonRepository($model, $data);

        return $personFactory->update();
    }

    /**
     * @param Model $model
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Model $model): ?bool
    {
        $personFactory = new PersonRepository($model);

        return $personFactory->delete();
    }

    /**
     * @param $data
     * @param $userId
     * @param Actor $actor
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rateActor($data, $userId, Actor $actor)
    {
        $rate = Arr::get($data, 'rate');
        $rating = null;
        if (!$actor->ratings()->where('user_id', $userId)->exists()) {
            $rating = $actor->ratings()->create([
                'user_id' => $userId,
                'rate' => $rate
            ]);
        }

        return $rating;
    }

    /**
     * @param $data
     * @param $userId
     * @param $actorId
     * @param $movieId
     * @return null
     */
    public function rateActorForMovie($data, $userId, $actorId, $movieId)
    {
        $rate = Arr::get($data, 'rate');
        $rating = null;
        $actorPlayedInMovie = ActorMovie::getActorForMovie($actorId, $movieId);
        if (!$actorPlayedInMovie->ratings()->where('user_id', $userId)->exists()) {
            $rating = $actorPlayedInMovie->ratings()->create([
                'user_id' => $userId,
                'rate' => $rate
            ]);
        }

        return $rating;
    }

    /**
     * @param $actorId
     * @param $movieId
     * @return float
     */
    public function calculateMovieRating($actorId, $movieId)
    {
        $actorPlayedInMovie = ActorMovie::getActorForMovie($actorId, $movieId);
        $actorForMovieAvgRating = $actorPlayedInMovie->ratings()->avg('rate');

        return round($actorForMovieAvgRating, 2);
    }
}
