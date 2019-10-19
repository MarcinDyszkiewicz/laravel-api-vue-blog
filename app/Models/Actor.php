<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Actor
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $poster
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movie[] $movies
 * @property-read int|null $movies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rating[] $ratings
 * @property-read int|null $ratings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Actor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Actor extends Model
{
    protected $fillable = ['full_name', 'poster', 'slug'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }
}
