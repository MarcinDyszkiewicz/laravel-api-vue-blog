<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MovieCreateUpdateRequest;
use App\Http\Requests\MovieIndexRequest;
use App\Http\Resources\MovieResourceListing;
use App\Models\Movie;
use App\Services\MovieService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController
{
    private MovieService $movieService;

    /**
     * MovieController constructor.
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(MovieIndexRequest $request)
    {
        $movies = Movie::all();

        return MovieResourceListing::collection($movies)->additional(['message' => 'ok', 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieCreateUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieCreateUpdateRequest $request)
    {
        try {
            $movie = $this->movieService->createMovie($request->all());

            return response()->json(['data' => $movie, 'message' => 'Movie Saved', 'success' => true ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Movie $movie
     * @return Movie
     */
    public function show(Movie $movie)
    {
        return $movie;
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\StreamInterface
     * @throws GuzzleException
     */
    public function findSingle(Request $request)
    {
        $movie = $this->movieService->showSingleMovie($request->all());

        return $movie;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Movie $movie
     * @return Movie
     */
    public function update(Request $request, Movie $movie)
    {
        $movie = $this->movieService->updateMovie($request->all(), $movie);

        return $movie;
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

    public function rate(Request $request, Movie $movie)
    {
        $userId = auth()->id();
        $rating = $this->movieService->rateMovie($request->all(), $userId, $movie);

        return $rating;
    }

    public function calculateRating(Movie $movie)
    {
        $averageRating = $this->movieService->calculateMovieRating($movie);

        return $averageRating;
    }
}
