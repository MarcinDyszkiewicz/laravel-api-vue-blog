<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PostService
{
    public function createPost($data, $userId)
    {
        $slug = Arr::get($data, 'slug');
        $title =  Arr::get($data, 'title');
        $published = Arr::get($data, 'published', false);
        $movieId = Arr::get($data, 'movieId');
        $categoryIds = Arr::get($data, 'categoryIds', []);
        $tagNames = Arr::get($data, 'tags', []);
        $movie = Movie::find($movieId);
        $post = Post::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => Arr::get($data, 'body'),
            'image' => Arr::get($data, 'image'),
            'meta_title' => Arr::get($data, 'metaTitle'),
            'meta_description' => Arr::get($data, 'metaDescription'),
            'summary' => Arr::get($data, 'summary'),
            'slug' => $slug ?? str_slug($title),
            'published' => $published,
            'published_at' => $published ? Carbon::now() : null
        ]);

        if ($movie) {
            $post->movie()->associate($movie);
            $post->save();
        }

        $post->categories()->sync(array_wrap($categoryIds));

        if (!empty(array_wrap($tagNames))) {
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => ucwords(strtolower($tagName))]);
                array_push($tagIds, $tag->id);
            }
            $post->tags()->sync($tagIds);
        }

        return $post;
    }

    public function updatePost($data, $userId, Post $post)
    {
        $slug = Arr::get($data, 'slug');
        $title =  Arr::get($data, 'title');
        $published = Arr::get($data, 'published', false);
        $movieId = Arr::get($data, 'movieId');
        $categoryIds = Arr::get($data, 'categoryIds', []);
        $tagNames = Arr::get($data, 'tags', []);
        if (optional($post->movie)->id != $movieId) {
            $movie = Movie::find($movieId);
        } else {
            $movie = null;
        }

        $post->update([
            'user_id' => $userId,
            'title' => $title,
            'body' => Arr::get($data, 'body'),
            'image' => Arr::get($data, 'image'),
            'meta_title' => Arr::get($data, 'metaTitle'),
            'meta_description' => Arr::get($data, 'metaDescription'),
            'summary' => Arr::get($data, 'summary'),
            'slug' => $slug ?? str_slug($title),
            'published' => $published,
            'published_at' => $published ? Carbon::now() : null
        ]);

        if ($movie) {
            $post->movie()->dissociate();
            $post->movie()->associate($movie);
            $post->save();
        }

        $post->categories()->sync(array_wrap($categoryIds));

        if (!empty(array_wrap($tagNames))) {
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => ucwords(strtolower($tagName))]);
                array_push($tagIds, $tag->id);
            }
            $post->tags()->sync($tagIds);
        }

        return $post;
    }
}
