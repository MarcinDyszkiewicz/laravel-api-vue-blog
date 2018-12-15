<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Services\MovieService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $GuzzleClient = new Client();
//        $response = $GuzzleClient->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
//            'query' => [
//                'apikey' => 'c9d3739b',
//                't' => 'batman',
//            ]
//        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $movie = $this->movieService->createMovie();

        return $movie;
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->first();

        if (!$movie) {
            $GuzzleClient = new Client();
            $response = $GuzzleClient->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
                'query' => [
                    'apikey' => 'c9d3739b',
                    't' => 'batman',
                ]
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchMovie()
    {
//        Movie::searchByTitle
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function searchInOmdb(Request $request)
    {
        try {
            return $this->movieService->findInOmdb($request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
