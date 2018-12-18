<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActorMovie extends Pivot
{
//    protected $table = '';

    public function rating()
    {
        return $this->morphOne(Rating::class, 'ratingable');
    }

    public static function getActorForMovie($actorId, $movieId)
    {
        return self::query()->where('actor_id', $actorId)->where('movie_id', $movieId)->first();
    }
}
