<?php

namespace App\Repositories\Genre;

use App\Models\Genre;
use App\Repositories\BaseRepository;
use App\Repositories\GenreInterface\GenreRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    /**
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listWithMovies(array $parameters)
    {
        return Genre::with('movies')->get();
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function create(array $parameters): Genre
    {
        $this->validate($parameters);

        $genre = Genre::create($this->parseParameters($parameters));

        return $genre;
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function update($genre, array $parameters): Genre
    {
        $this->validate($parameters);

        /** @var Genre $genre */
        $genre->update($this->parseParameters($parameters));

        return $genre;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function delete($genre): ?bool
    {
        return $genre->delete();
    }

    /**
     * @inheritDoc
     */
    protected function parseParameters(array $parameters): array
    {
        $parameters['slug'] ??= str_slug($parameters['name']);

        return $parameters;
    }

    /**
     * @inheritDoc
     */
    protected function validationRules(array $parameters = []): array
    {
        return [
            'name' => 'required|string|max:255|unique:genres,name',
            'slug' => 'nullable|string|max:255|unique:genres,slug',
        ];
    }
}
