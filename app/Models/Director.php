<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Director
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $poster
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movie[] $movies
 * @property-read int|null $movies_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Director whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Director extends Model
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
}
