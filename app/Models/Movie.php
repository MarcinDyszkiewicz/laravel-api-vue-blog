<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property string $title
 * @property int|null $year
 * @property string|null $released
 * @property int|null $runtime
 * @property string|null $plot
 * @property string|null $review
 * @property string|null $poster
 * @property string|null $internet_movie_database_rating
 * @property string|null $rotten_tomatoes_rating
 * @property string|null $metacritic_rating
 * @property string|null $imdb_rating
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Actor[] $actors
 * @property-read int|null $actors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Director[] $directors
 * @property-read int|null $directors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read int|null $genres_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rating[] $ratings
 * @property-read int|null $ratings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereImdbRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereInternetMovieDatabaseRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereMetacriticRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie wherePlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereReleased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereRottenTomatoesRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereRuntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereYear($value)
 * @mixin \Eloquent
 */
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
