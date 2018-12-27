<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $posts = Post::listAllPublished();

        return PostResource::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $post = $this->postService->createPost($request->all(), $userId);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $userId = auth()->id();
        $post = $this->postService->updatePost($request->all(), $userId, $post);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::destroy(array_wrap($id));
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listHotCategory()
    {
        $posts = Post::listForHotCategory();

        return PostResource::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listHomepage()
    {
        $posts = Post::listForHomepage();

        return PostResource::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    public function listSimilar(Post $post)
    {
        $posts = Post::listSimilar($post);

        return PostResource::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }
}
