<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Like;
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
                $comment = $post->comments()->create([
                    'user_id' => $userId,
                    'body' => $body,
                    'comment_parent_id' => $commentParentId
                ]);
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


    public function updateComment($data, $userId, Comment $comment)
    {
        $body = array_get($data, 'body');
        $postId = array_get($data, 'postId');
        $movieId = array_get($data, 'movieId');

        if ($postId) {
            $post = Post::find($postId);
            abort_if(!$post, 404, 'Post not found');
            abort_if($post->comments()->where('user_id', $userId)->where('body', $body)->exists(), 400, 'You Can\'t duplicate comments');
            $comment->update([
                'body' => $body,
            ]);
        } elseif ($movieId) {
            $movie = Movie::find($movieId);
            abort_if(!$movie, 404, 'Movie not found');
            abort_if($movie->comments()->where('user_id', $userId)->where('body', $body)->exists(), 400, 'You Can\'t duplicate comments');
            $comment->update([
                'body' => $body,
            ]);
        }

        return $comment;
    }

    /**
     * @param Comment $comment
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     */
    public function likeComment(Comment $comment, $userId)
    {
        $like = $comment->likes()->where('user_id', $userId)->first();
        if ($like) {
            if ($like->type == Like::TYPE_DISLIKE) {
                $like->delete();
                $like = $this->createLike($comment->id, $userId, Like::TYPE_LIKE);
            } elseif ($like->type == Like::TYPE_LIKE) {
                $like = $like->delete();
            }
        } else {
            $like = $this->createLike($comment->id, $userId, Like::TYPE_LIKE);
        }

        return $like;
    }

    /**
     * @param Comment $comment
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     */
    public function dislikeComment(Comment $comment, $userId)
    {
        $like = $comment->likes()->where('user_id', $userId)->first();
        if ($like) {
            $like->delete();
            if ($like->type == Like::TYPE_LIKE) {
                $like = $this->createLike($comment->id, $userId, Like::TYPE_DISLIKE);
            }
        } else {
            $like = $this->createLike($comment->id, $userId, Like::TYPE_DISLIKE);
        }

        return $like;
    }

    /**
     * @param $commentId
     * @param $userId
     * @param $type
     * @return Like
     */
    private function createLike($commentId, $userId, $type)
    {
        $like = Like::create([
            'user_id' => $userId,
            'comment_id' => $commentId,
            'type' => $type
        ]);

        return $like;
    }

    /**
     * @param Comment $comment
     * @return int
     */
    public function countCommentLikesAndDislikes(Comment $comment)
    {
        $likesNumber = $comment->likes()->where('type', Like::TYPE_LIKE)->count();
        $dislikesNumber = $comment->likes()->where('type', Like::TYPE_DISLIKE)->count();

        return $likesNumber - $dislikesNumber;
    }
}