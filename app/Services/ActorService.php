<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\ActorMovie;
use App\Models\Movie;
use Illuminate\Support\Arr;

class ActorService
{
    /**
     * @param $data
     * @return mixed
     */
    public function createActor($data)
    {
        $actor = Actor::create([
            'full_name' => Arr::get($data, 'fullName'),
            'poster' => Arr::get($data, 'poster'),
        ]);
        $movieIds = Arr::get($data, 'movieIds');
        if (!empty($movieIds)) {
            $actor->movies()->attach(array_wrap($movieIds));
        }

        return $actor;
    }

    /**
     * @param $data
     * @param Actor $actor
     * @return Actor
     */
    public function updateActor($data, Actor $actor)
    {
        $actor->update([
            'full_name' => Arr::get($data, 'fullName'),
            'poster' => Arr::get($data, 'poster')
        ]);
        $movieIds = Arr::get($data, 'movieIds');
        if (!empty($movieIds)) {
            $actor->movies()->sync(array_wrap($movieIds));
        }

        return $actor;
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
