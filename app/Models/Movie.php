<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Actor[] $actors
 * @property-read int|null $actors_count
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|Director[] $directors
 * @property-read int|null $directors_count
 * @property-read Collection|Genre[] $genres
 * @property-read int|null $genres_count
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @property-read Collection|Rating[] $ratings
 * @property-read int|null $ratings_count
 * @method static Builder|Movie newModelQuery()
 * @method static Builder|Movie newQuery()
 * @method static Builder|Movie query()
 * @method static Builder|Movie whereCreatedAt($value)
 * @method static Builder|Movie whereId($value)
 * @method static Builder|Movie whereImdbRating($value)
 * @method static Builder|Movie whereInternetMovieDatabaseRating($value)
 * @method static Builder|Movie whereMetacriticRating($value)
 * @method static Builder|Movie wherePlot($value)
 * @method static Builder|Movie wherePoster($value)
 * @method static Builder|Movie whereReleased($value)
 * @method static Builder|Movie whereReview($value)
 * @method static Builder|Movie whereRottenTomatoesRating($value)
 * @method static Builder|Movie whereRuntime($value)
 * @method static Builder|Movie whereSlug($value)
 * @method static Builder|Movie whereTitle($value)
 * @method static Builder|Movie whereUpdatedAt($value)
 * @method static Builder|Movie whereYear($value)
 * @mixin Eloquent
 */
class Movie extends Model
{
    public const LIST_SELECT_COLUMNS = ['id', 'title', 'year', 'poster', 'slug', 'imdb_id'];

    protected $fillable = [
        'title',
        'year',
        'released',
        'runtime',
        'plot',
        'review',
        'poster',
        'internet_movie_database_rating',
        'rotten_tomatoes_rating',
        'metacritic_rating',
        'imdb_rating',
        'slug',
        'imdb_id'
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

    /**
     * @param  array  $data
     * @return Collection
     */
    public static function index(array $data): Collection
    {
        $orderBy = $data['order_by'] ??= 'released';
        $orderDir = $data['order_dir'] ??= 'desc';

        $movies = self::query()
            ->select(self::LIST_SELECT_COLUMNS)
            ->orderBy($orderBy, $orderDir)
            ->get();

        return $movies;
    }

    /**
     * @param  array  $data
     * @return Collection
     */
    public static function search(array $data): Collection
    {
        $title = $data['title'] ??= null;
        $year = $data['year'] ??= null;
        $orderBy = $data['order_by'] ??= 'released';
        $orderDir = $data['order_dir'] ??= 'desc';

        $movies = self::query()
            ->where('title', 'ILIKE', "%$title%")
            ->when($year, function ($query, $year) {
                $query->where('year', $year);
            })
            ->select(self::LIST_SELECT_COLUMNS)
            ->orderBy($orderBy, $orderDir)
            ->get();

        return $movies;
    }
}
