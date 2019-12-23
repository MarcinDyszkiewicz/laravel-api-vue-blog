<?php

namespace App\Repositories\Movie;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

interface MovieRepositoryInterface
{
    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function create(array $parameters): Movie;

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function update(Movie $movie, array $parameters): Movie;

    /**
     * @inheritDoc
     */
    public function delete(Movie $movie): ?bool;
}