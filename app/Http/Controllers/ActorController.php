<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActorStoreAndUpdateRequest;
use App\Managers\Person\PersonManager;
use App\Models\Actor;
use App\Models\Movie;
use App\Services\PersonService;
use App\Services\RatePersonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActorController extends Controller
{
    private $actorService;

    public function __construct(PersonService $actorService)
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
     * @param ActorStoreAndUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActorStoreAndUpdateRequest $request)
    {
        try {
            $actor = $this->actorService->createActor($request->only($request::allowedParams()));

            return response()->json($actor);
        } catch (ValidationException $e) {
            return response()->json($e->errorBag, 422);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Actor $actor
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        return response()->json($actor->load('movies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ActorStoreAndUpdateRequest $request
     * @param \App\Models\Actor $actor
     * @return \Illuminate\Http\Response
     */
    public function update(ActorStoreAndUpdateRequest $request, Actor $actor)
    {
        try {
            $actor = $this->actorService->updatePerson($actor, $request->only($request::allowedParams()));

            return response()->json($actor);
        } catch (ValidationException $e) {
            return response()->json($e->errorBag);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Actor $actor
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Actor $actor)
    {
        try {
            $this->actorService->delete($actor);
            return response()->json(['data' => null, 'message' => 'Person Deleted', 'success' => true], JsonResponse::HTTP_OK);
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
        try {
            $userId = auth()->id();
            $ratePersonService = new RatePersonService($request->input('rate'), $actor, $userId = 1);
            $rating = $ratePersonService->ratePerson();

            return response()->json(['data' => $rating, 'message' => 'Person Rated', 'success' => true], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @param Actor $actor
     * @param Movie $movie
     * @return null
     */
    public function rateForMovie(Request $request, Actor $actor, Movie $movie)
    {
        try {
            $userId = auth()->id();
            $ratePersonService = new RatePersonService($request->input('rate'), $actor, $userId = 1);
            $rating = $ratePersonService->rateActorForMovie($movie->id);

            return response()->json(['data' => $rating, 'message' => 'Person Rated', 'success' => true], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e->getMessage(), 'success' => false], JsonResponse::HTTP_BAD_REQUEST);
        }
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
