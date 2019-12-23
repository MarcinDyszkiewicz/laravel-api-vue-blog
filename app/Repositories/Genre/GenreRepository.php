<?php

namespace App\Repositories\Genre;

use App\Models\Genre;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class GenreRepository extends BaseRepository
{
    public function listWithMovies(array $parameters)
    {
        return Genre::with('movies')->get();
    }

    /**
     * @inheritDoc
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(array $parameters): Genre
    {
        $this->validate($parameters);

        $genre = Genre::create($this->parseParameters($parameters));

        return $genre;
    }

    /**
     * @inheritDoc
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($genre, array $parameters): Genre
    {
        $this->validate($parameters);

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
        // TODO: Implement parseParameters() method.
    }

    /**
     * @inheritDoc
     */
    protected function validationRules(array $parameters = []): array
    {
        // TODO: Implement validationRules() method.
    }
}