<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movie[] $movies
 * @property-read int|null $movies_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Genre extends Model
{
    protected $fillable = ['name', 'slug'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
