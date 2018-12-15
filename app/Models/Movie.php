<?php

namespace App;

use App\Models\Actor;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title', 'year', 'released', 'runtime', 'plot', 'review', 'poster',
        'internet_movie_database_rating', 'rotten_tomatoes_rating', 'metacritic_rating', 'imdb_rating', 'slug'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }
}
