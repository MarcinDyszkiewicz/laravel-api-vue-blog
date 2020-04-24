<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Repositories\Genre\GenreRepositoryInterface;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    private GenreRepositoryInterface $genreRepository;

    public function __construct(GenreRepositoryInterface $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $genres = $this->genreRepository->listWithMovies($request->all());

        return response()->json($genres);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $genre = $this->genreRepository->create($request->all());

        return response()->json($genre);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        $genre->load('movies');
        return response()->json($genre);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        $this->genreRepository->update($request->all());

        return response()->json($genre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre $genre
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Genre $genre)
    {
        try {
            $genre->delete();
            return response()->json('Genre Deleted');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
