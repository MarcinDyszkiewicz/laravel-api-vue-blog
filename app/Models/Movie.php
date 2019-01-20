<?php

namespace App\Models;

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

    public function directors()
    {
        return $this->belongsToMany(Director::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
