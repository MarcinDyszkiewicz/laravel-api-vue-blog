<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Post;

class PostService
{
    public function createPost($data, $userId)
    {
        $movieId = array_get($data, 'movieId');
        $movie = Movie::find($movieId)->exists();
        $post = Post::create([
            'user_id' => $userId,
            'title' => array_get($data, 'title'),
            'body' => array_get($data, 'body'),
            'image' => array_get($data, 'image'),
            'meta_title' => array_get($data, 'meta_title'),
            'meta_description' => array_get($data, 'meta_description'),
            'summary' => array_get($data, 'summary'),
            'slug' => array_get($data, 'slug')
        ]);

        if ($movie) {
            $post->attach($movieId);
            $post->save();
        }

        return $post;
    }

    public function updatePost($data, $userId, Post $post)
    {
        $movieId = array_get($data, 'movieId');
        $movie = Movie::find($movieId)->exists();
        $post->update([
            'user_id' => $userId,
            'title' => array_get($data, 'title'),
            'body' => array_get($data, 'body'),
            'image' => array_get($data, 'image'),
            'meta_title' => array_get($data, 'meta_title'),
            'meta_description' => array_get($data, 'meta_description'),
            'summary' => array_get($data, 'summary'),
            'slug' => array_get($data, 'slug')
        ]);

        if ($movie) {
            $post->sync(array_wrap($movieId));
            $post->save();
        }

        return $post;
    }
}