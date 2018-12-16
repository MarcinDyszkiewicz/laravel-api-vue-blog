<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\Post;

class CommentService
{
    /**
     * @param $data
     * @param $userId
     * @return null|Comment
     */
    public function createComment($data, $userId)
    {
        $body = array_get($data, 'body');
        $postId = array_get($data, 'postId');
        $movieId = array_get($data, 'movieId');
        $commentParentId = array_get($data, 'commentParentId');
        $comment = null;

        if ($postId) {
            $post = Post::find($postId);
            abort_if(!$post, 404, 'Post not found');
            abort_if($post->comments()->where('user_id', $userId)->where('body', $body)->exists(), 400, 'You Can\'t duplicate comments');
//            if (!$post->comments()->where('user_id', $userId)->exists()) {
                $comment = $post->comments()->create([
                    'user_id' => $userId,
                    'body' => $body,
                    'comment_parent_id' => $commentParentId
                ]);
//            }
        } elseif ($movieId) {
            $movie = Movie::find($movieId);
            abort_if(!$movie, 404, 'Movie not found');
            abort_if($movie->comments()->where('user_id', $userId)->where('body', $body)->exists(), 400, 'You Can\'t duplicate comments');
            $comment = $movie->comments()->create([
                    'user_id' => $userId,
                    'body' => $body,
                    'comment_parent_id' => $commentParentId
                ]);
        }

        return $comment;
    }
}