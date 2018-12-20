<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
use App\Services\MovieService;
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
        $movie = $this->movieService->createMovie($request->all());

        return $movie;
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
