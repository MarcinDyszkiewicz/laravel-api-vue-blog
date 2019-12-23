<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository implements BaseRepositoryInterface
{
//    /**
//     * @var Model
//     */
//    protected Model $model;
//    /**
//     * @var array
//     */
//    protected array $parameters;
//
//    public function __construct(Model $model, array $parameters = [])
//    {
//        $this->model = $model;
//        $this->parameters = $parameters;
//    }

    /**
     * @param  array  $parameters
     * @return Model
     */
    abstract public function create(array $parameters): Model;

    /**
     * @param  Model  $model
     * @param  array  $parameters
     * @return Model
     */
    abstract public function update(Model $model, array $parameters): Model;

    /**
     * @param  Model  $model
     * @return bool|null
     */
    abstract public function delete(Model $model): ?bool;

    /**
     * @param  array  $parameters
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $parameters): bool
    {
        $validator = Validator::make($parameters, $this->validationRules($parameters));

        if ($validator->fails()) {
            throw new ValidationException($validator, $validator->getMessageBag(), $validator->errors());
        }

        return true;
    }

    /**
     * @param  array  $parameters
     * @return array
     */
    abstract protected function parseParameters(array $parameters): array;

    /**
     * @param  array  $parameters
     * @return array
     */
    abstract protected function validationRules(array $parameters = []): array;
}
