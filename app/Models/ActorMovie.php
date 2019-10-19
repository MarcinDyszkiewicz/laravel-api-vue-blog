<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\ActorMovie
 *
 * @property int $id
 * @property int $actor_id
 * @property int $movie_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rating[] $ratings
 * @property-read int|null $ratings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie whereActorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActorMovie whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActorMovie extends Pivot
{
//    protected $table = '';

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public static function getActorForMovie($actorId, $movieId)
    {
        return self::query()->where('actor_id', $actorId)->where('movie_id', $movieId)->first();
    }
}
