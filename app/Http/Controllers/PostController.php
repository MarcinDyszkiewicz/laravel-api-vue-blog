<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostResourceListing;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
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

        return PostResourceListing::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCreateUpdateRequest $request
     * @return PostResource
     */
    public function store(PostCreateUpdateRequest $request)
    {
        $userId = auth()->id();
        $post = $this->postService->createPost($request->all(), $userId);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        $post->load('tags', 'categories');

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostCreateUpdateRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(PostCreateUpdateRequest $request, Post $post)
    {
        $userId = auth()->id();
        $post = $this->postService->updatePost($request->all(), $userId, $post);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post)
    {
        try {
            Post::destroy(array_wrap($post->id));

            return response()->json(['data' => null, 'message' => 'Ok', 'success' => true ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false ], $e->getCode());
        }
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listHotCategory()
    {
        $posts = Post::listForHotCategory();

        return PostResourceListing::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listHomepage()
    {
        $posts = Post::listForHomepage();

        return PostResourceListing::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listSimilar(Post $post)
    {
        $posts = Post::listSimilar($post);

        return PostResourceListing::collection($posts)->additional(['message' => 'ok', 'success' => true]);
    }
}
