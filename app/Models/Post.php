<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $fillable = ['user_id', 'movie_id', 'title', 'body', 'image', 'meta_title', 'meta_description', 'summary', 'slug', 'is_published'];
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
