<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = $this->commentService->createComment($request->all(), auth()->id());

        return response()->json($comment);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
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
