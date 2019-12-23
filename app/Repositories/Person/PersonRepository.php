<?php

namespace App\Repositories\Person;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PersonRepository extends BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param  array  $parameters
     * @return Model
     * @throws ValidationException
     */
    public function create(array $parameters): Model
    {
        $this->validate($parameters);

        $model = $this->model->create($this->parseParameters($parameters));

        $this->attachToMovie($model, $parameters['movie_ids']);

        return $model;
    }

    /**
     * @param  Model  $model
     * @param  array  $parameters
     * @return Model
     * @throws ValidationException
     */
    public function update(Model $model, array $parameters): Model
    {
        $this->validate($parameters);

        $model->update($this->parseParameters($parameters));

        $this->syncWithMovie($model, $parameters['movie_ids']);

        return $model;
    }

    /**
     * @param  Model  $model
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    /**
     * @param  Model  $model
     * @param  array  $movieIds
     */
    public function attachToMovie(Model $model, array $movieIds): void
    {
        if (filled($movieIds)) {
            $model->movies()->attach($movieIds);
        }
    }

    /**
     * @param  array  $parameters
     * @return array
     */
    protected function parseParameters(array $parameters): array
    {
        $fullName = Arr::get($parameters, 'full_name');
        $slug = Arr::get($parameters, 'slug');
        return [
            'full_name' => $fullName,
            'poster' => Arr::get($parameters, 'poster'),
            'slug' => $slug ?? Str::slug($fullName, '-'),
        ];
    }

    /**
     * @param  array  $params
     * @return array
     */
    protected function validationRules(array $params = []): array
    {
        $modelTable = $this->model->getTable();
        return [
            'full_name' =>
                "required|string|unique:{$modelTable},full_name|regex:/(?=^.{5,50}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/",
            'poster' => 'nullable|string|url',
            'movie_ids' => 'present|array',
            'movie_ids.*' => 'nullable|integer|exists:movies,id'
        ];
    }

    /**
     * @param  Model  $model
     * @param  array  $movieIds
     */
    private function syncWithMovie(Model $model, array $movieIds): void
    {
        if (filled($movieIds)) {
            $model->movies()->sync($movieIds);
        }
    }
}
