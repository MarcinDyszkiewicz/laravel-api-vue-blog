<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceListing;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Movie;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentCreateUpdateRequest $request
     * @return CommentResource
     */
    public function store(CommentCreateUpdateRequest $request)
    {
        $comment = $this->commentService->createComment($request->all(), auth()->id());

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentCreateUpdateRequest $request
     * @param  \App\Models\Comment $comment
     * @return CommentResource
     */
    public function update(CommentCreateUpdateRequest $request, Comment $comment)
    {
        $comment = $this->commentService->updateComment($request->all(), auth()->id(), $comment);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();

            return response()->json(['data' => null, 'message' => 'Comment deleted', 'success' => true ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false ], $e->getCode());
        }
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function commentsForPost(Post $post)
    {
        $comments = $post->comments;

        return CommentResourceListing::collection($comments)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * @param Movie $movie
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function commentsForMovie(Movie $movie)
    {
        $comments = $movie->comments;

        return CommentResourceListing::collection($comments)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function likeOrDislike(Request $request, Comment $comment)
    {
        $userId = auth()->id();
        if ($request->likeType == Like::TYPE_LIKE) {
            $like = $this->commentService->likeComment($comment, $userId);
        } elseif ($request->likeType == Like::TYPE_DISLIKE) {
            $like = $this->commentService->dislikeComment($comment, $userId);
        }

        return response()->json(['data' => $like, 'success' => true, 'message' => 'Ok'], JsonResponse::HTTP_OK);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function likesCount(Comment $comment)
    {
        $likesCount = $this->commentService->countCommentLikesAndDislikes($comment);

        return response()->json(['data' => $likesCount, 'success' => true, 'message' => 'Ok'], JsonResponse::HTTP_OK);
    }
}
