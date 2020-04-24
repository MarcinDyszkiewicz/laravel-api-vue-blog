<?php

namespace App\Repositories\Genre;

use App\Models\Genre;
use Illuminate\Validation\ValidationException;

interface GenreRepositoryInterface
{
    public function listWithMovies(array $parameters);

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function create(array $parameters): Genre;

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function update($genre, array $parameters): Genre;

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function delete($genre): ?bool;
}
