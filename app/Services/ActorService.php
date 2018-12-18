<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\ActorMovie;
use App\Models\Movie;

class ActorService
{
    /**
     * @param $data
     * @return mixed
     */
    public function createActor($data)
    {
        $actor = Actor::create([
            'full_name' => array_get($data, 'fullName'),
            'poster' => array_get($data, 'poster'),
        ]);
        $movieIds = array_get($data, 'movieIds');
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
            'full_name' => array_get($data, 'fullName'),
            'poster' => array_get($data, 'poster')
        ]);
        $movieIds = array_get($data, 'movieIds');
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
        $rate = array_get($data, 'rate');
        $rating = null;
        if (!$actor->rating()->where('user_id', $userId)->exists()) {
            $rating = $actor->rating()->create([
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
        $rate = array_get($data, 'rate');
        $rating = null;
        $actorPlayedInMovie = ActorMovie::getActorForMovie($actorId, $movieId);
        if (!$actorPlayedInMovie->rating()->where('user_id', $userId)->exists()) {
            $rating = $actorPlayedInMovie->rating()->create([
                'user_id' => $userId,
                'rate' => $rate
            ]);
        }

        return $rating;
    }

}