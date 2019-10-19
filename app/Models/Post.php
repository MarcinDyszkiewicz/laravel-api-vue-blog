<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $movie_id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $image
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $summary
 * @property string|null $short_description
 * @property bool $is_published
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Movie|null $movie
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUserId($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    protected $fillable = ['user_id', 'movie_id', 'title', 'body', 'image', 'meta_title', 'meta_description', 'summary', 'slug', 'is_published', 'published_at'];
//@@todo dodać body skrócone
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function listAllPublished()
    {
        $posts = self::query()
            ->leftJoin('users as u', 'posts.user_id', '=', 'u.id')
            ->where('is_published', '=', true)
            ->select('posts.id as id', 'title', 'image', 'summary', 'slug', 'u.id as userId', 'published_at', 'u.name as userName')
            ->orderByDesc('published_at')
            ->paginate(20);

        return $posts;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function listForHomepage()
    {
        $posts = self::query()
            ->leftJoin('users as u', 'posts.user_id', '=', 'u.id')
            ->where('is_published', '=', true)
            ->select('posts.id as id', 'title', 'image', 'summary', 'slug', 'u.id as userId', 'u.name as userName')
            ->orderByDesc('published_at')
            ->limit(10)
            ->get();

        return $posts;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function listForHotCategory()
    {
        $posts = self::query()
            ->leftJoin('users as u', 'posts.user_id', '=', 'u.id')
            ->where('is_published', '=', true)
            ->whereHas('categories', function ($query) {
                $query->where('name', '=', 'Hot');
            })
            ->select('posts.id as id', 'title', 'image', 'summary', 'slug', 'u.id as userId', 'u.name as userName')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        return $posts;
    }

    /**
     * @param $categoryName
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function listForCategory($categoryName)
    {
        $posts = self::query()
            ->leftJoin('users as u', 'posts.user_id', '=', 'u.id')
            ->where('is_published', '=', true)
            ->whereHas('categories', function ($query) use ($categoryName) {
                $query->where('name', '=', $categoryName);
            })
            ->select('posts.id as id', 'title', 'image', 'summary', 'slug', 'u.id as userId', 'u.name as userName')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        return $posts;
    }

    /**
     * @param Post $post
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function listSimilar(Post $post)
    {
        $tagNames = $post->tags()->pluck('name');

        $posts = self::query()
            ->leftJoin('users as u', 'posts.user_id', '=', 'u.id')
            ->leftJoin('post_tag as pt', 'posts.user_id', '=', 'u.id')
            ->where('is_published', '=', true)
            ->whereHas('tags', function ($query) use ($tagNames) {
                $query->whereIn('name', $tagNames);
            })
            ->where('posts.id', '!=', $post->id)
            ->select('posts.id as id', 'title', 'image', 'summary', 'slug', 'u.id as userId', 'u.name as userName', DB::raw('count(*) as matching_tags'))
            ->distinct()
            ->groupBy('posts.id', 'title', 'image', 'summary', 'slug', 'userId', 'userName')
            ->orderByDesc('matching_tags')
            ->limit(5)
            ->get();

        return $posts;
    }
}
