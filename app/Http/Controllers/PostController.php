<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
//        $response = file_get_contents('http://www.omdbapi.com/?apikey=c9d3739b');
        $client = new Client();
        $response = $client->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
            'query' => [
                'apikey' => 'c9d3739b',
                't' => 'batman',
            ]
        ]);
//        echo $res->getStatusCode();
//        // 200
//        echo $res->getHeader('content-type');
//        // 'application/json; charset=utf8'
//        echo $res->getBody();


        return $response->getBody();

        $posts = Post::with('user')->get();

//        return response()->json($posts);
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
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = request()->user()->id;
        $post->save();

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

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
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = request()->user()->id;
        $post->save();

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
        Post::destroy([$id]);
    }
}
