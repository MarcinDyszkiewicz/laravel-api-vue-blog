<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;
    /**
     * @var array
     */
    protected array $parameters;

    public function __construct(Model $model, array $parameters = [])
    {
        $this->model = $model;
        $this->parameters = $parameters;
    }

    /**
     * @return Model
     * @throws ValidationException
     */
    abstract public function create(): Model;

    /**
     * @return Model
     * @throws ValidationException
     */
    abstract public function update(): Model;

    /**
     * @return bool|null
     */
    abstract public function delete(): ?bool;

    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        $validator = Validator::make($this->parameters, $this->validationRules());

        if ($validator->fails()) {
            throw new ValidationException($validator, $validator->getMessageBag(), $validator->errors());
        }

        return true;
    }

    /**
     * @return array
     */
    abstract protected function parseParameters(): array;

    /**
     * @return array
     */
    abstract protected function validationRules(): array;
}
