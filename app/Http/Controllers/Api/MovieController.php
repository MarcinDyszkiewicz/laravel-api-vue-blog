<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MovieCreateFromOmdbRequest;
use App\Http\Requests\MovieCreateUpdateRequest;
use App\Http\Requests\MovieIndexRequest;
use App\Http\Requests\MovieSearchRequest;
use App\Http\Resources\MovieResource;
use App\Http\Resources\MovieResourceListing;
use App\Http\Responses\ExceptionResponse;
use App\Http\Responses\MyJsonResponse;
use App\Http\Responses\ValidationExceptionResponse;
use App\Models\Movie;
use App\Omdb\Omdb;
use App\Repositories\Movie\MovieRepositoryInterface;
use App\Services\MovieService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovieController
{
    private MovieService $movieService;

    /**
     * MovieController constructor.
     * @param  MovieService  $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  MovieIndexRequest  $request
     * @return MovieResourceListing|ExceptionResponse
     */
    public function index(MovieIndexRequest $request)
    {
        try {
            $movies = Movie::index($request->onlyAllowedParams());

            return MovieResourceListing::make($movies)->additional(['message' => 'ok', 'success' => true]);
        } catch (\Exception $e) {
            return (new ExceptionResponse($e));
        }
    }

    /**
     * @param  MovieSearchRequest  $request
     * @param  MovieService  $movieService
     * @param  Omdb  $omdb
     * @return MovieResourceListing|ExceptionResponse
     */
    public function search(MovieSearchRequest $request, MovieService $movieService, Omdb $omdb)
    {
        try {
            $allowedParams = $request->onlyAllowedParams();
            $dbMovies = Movie::search($allowedParams)->toArray();
            $omdbMovies = $omdb->search($allowedParams);
            $movies = array_merge($dbMovies, $omdbMovies);
            $movies = $movieService->uniqueMoviesByImdbId($movies);

            return MovieResourceListing::make($movies)->additional(['message' => 'ok', 'success' => true]);
        } catch (\Exception $e) {
            return (new ExceptionResponse($e));
        }
    }

    /**
     * @param  Request  $request
     * @param  Omdb  $omdb
     * @return JsonResponse
     */
    public function getFromOmdb(Request $request, Omdb $omdb)
    {
        try {
            $movie = $omdb->findById($request->input('omdb_id'));

            return new MyJsonResponse($movie);
        } catch (\Exception $e) {
            return (new ExceptionResponse($e));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MovieCreateUpdateRequest  $request
     * @param  MovieRepositoryInterface  $movieRepository
     * @return MovieResource|JsonResponse
     * @throws ValidationException
     */
    public function store(MovieCreateUpdateRequest $request, MovieRepositoryInterface $movieRepository)
    {
        try {
            $movie = $movieRepository->create($request->onlyAllowedParams());

            return MovieResource::make($movie)->additional(['message' => 'Movie Saved', 'success' => true]);
        } catch (ValidationException $e) {
            return new ValidationExceptionResponse($e);
        } catch (\Exception $e) {
            return new ExceptionResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MovieCreateFromOmdbRequest  $request
     * @param  MovieRepositoryInterface  $movieRepository
     * @param  Omdb  $omdb
     * @return MovieResource|JsonResponse
     */
    public function storeFromOmdb(
        MovieCreateFromOmdbRequest $request,
        MovieRepositoryInterface $movieRepository,
        Omdb $omdb
    ) {
        try {
            $omdbMovie = $omdb->findById($request->input('omdb_id', ''));
            $movie = $movieRepository->create($omdbMovie->toArray());

            return MovieResource::make($movie)->additional(['message' => 'Movie Saved', 'success' => true]);
        } catch (ValidationException $e) {
            return new ValidationExceptionResponse($e);
        } catch (\Exception $e) {
            return new ExceptionResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Movie  $movie
     * @return MovieResource
     */
    public function show(Movie $movie)
    {
        return MovieResource::make($movie->load(['genres', 'actors', 'directors']))->additional(['message' => 'Ok', 'success' => true]);
    }

    /**
     * @param  Request  $request
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
     * @param  MovieCreateUpdateRequest  $request
     * @param  Movie  $movie
     * @param  MovieRepositoryInterface  $movieRepository
     * @return MovieResource|ExceptionResponse|ValidationExceptionResponse
     */
    public function update(MovieCreateUpdateRequest $request, Movie $movie, MovieRepositoryInterface $movieRepository)
    {
        try {
            $movie = $movieRepository->update($movie, $request->onlyAllowedParams());

            return MovieResource::make($movie)->additional(['message' => 'Movie Updated', 'success' => true]);
        } catch (ValidationException $e) {
            return new ValidationExceptionResponse($e);
        } catch (\Exception $e) {
            return (new ExceptionResponse($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Movie  $movie
     * @param  MovieRepositoryInterface  $movieRepository
     * @return MovieResource|ExceptionResponse|MyJsonResponse
     */
    public function destroy(Movie $movie, MovieRepositoryInterface $movieRepository)
    {
        try {
            $deleted = $movieRepository->delete($movie);

            return new MyJsonResponse($deleted, true, 'Movie Delete');
        } catch (\Exception $e) {
            return (new ExceptionResponse($e));
        }
    }

    public function searchMovie()
    {
//        Movie::searchByTitle
    }

    /**
     * @param  Request  $request
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
