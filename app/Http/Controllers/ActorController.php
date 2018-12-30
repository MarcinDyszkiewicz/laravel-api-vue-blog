<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Movie;
use App\Services\ActorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    private $actorService;

    public function __construct(ActorService $actorService)
    {
        $this->actorService = $actorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Actor[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return response()->json(Actor::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $actor = $this->actorService->createActor($request->all());

        return response()->json($actor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        return response()->json($actor->load('movies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actor $actor)
    {
        $actor = $this->actorService->updateActor($request->all(), $actor);

        return response()->json($actor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Actor $actor
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Actor $actor)
    {
        try {
            $actor->delete();
            return response()->json(['data' => null, 'message' => 'Actor Deleted', 'success' => true], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @param Actor $actor
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rate(Request $request, Actor $actor)
    {
        $userId = auth()->id();
        $rating = $this->actorService->rateActor($request->all(), $userId, $actor);

        return $rating;
    }

    /**
     * @param Request $request
     * @param Actor $actor
     * @param Movie $movie
     * @return null
     */
    public function rateForMovie(Request $request, Actor $actor, Movie $movie)
    {
        $userId = auth()->id();
        $rating = $this->actorService->rateActorForMovie($request->all(), $userId, $actor->id, $movie->id);

        return $rating;
    }

    /**
     * @param Actor $actor
     * @param Movie $movie
     * @return float
     */
    public function calculateForMovieRating(Actor $actor, Movie $movie)
    {
        $averageRating = $this->actorService->calculateMovieRating($actor->id, $movie->id);

        return $averageRating;
    }
}
