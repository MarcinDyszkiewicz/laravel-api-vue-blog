<?php

namespace App\Services;

use App\Models\Actor;

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
}