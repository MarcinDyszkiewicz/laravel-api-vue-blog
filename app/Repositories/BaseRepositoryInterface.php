<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * @param  array  $parameters
     * @return Model
     */
    public function create(array $parameters): Model;

    /**
     * @param  Model  $model
     * @param  array  $parameters
     * @return Model
     */
    public function update(Model $model, array $parameters): Model;

    /**
     * @param  array  $parameters
     * @return bool
     */
    public function validate(array $parameters): bool;
}
