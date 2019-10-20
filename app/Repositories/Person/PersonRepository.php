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
     * @return Model
     * @throws ValidationException
     */
    public function create(): Model
    {
        $this->validate();

        $this->model = $this->model->create($this->parseParameters());

        $this->attachToMovie();

        return $this->model;
    }

    /**
     * @return Model
     * @throws ValidationException
     */
    public function update(): Model
    {
        $this->validate();

        $this->model = $this->model->update($this->parseParameters());

        $this->syncWithMovie();

        return $this->model;
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }

    public function attachToMovie(): void
    {
        if (isset($this->parameters['movie_ids'])) {
            $this->model->movies()->attach($this->parameters['movie_ids']);
        }
    }

    /**
     * @return array
     */
    protected function parseParameters(): array
    {
        $fullName = Arr::get($this->parameters, 'full_name');
        $slug = Arr::get($this->parameters, 'slug');
        return [
            'full_name' => $fullName,
            'poster' => Arr::get($this->parameters, 'poster'),
            'slug' => $slug ?? Str::slug($fullName, '-'),
        ];
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        $modelTable = $this->model->getTable();
        return [
            'full_name' =>
                "required|string|unique:{$modelTable},full_name|regex:/(?=^.{5,50}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/",
            'poster' => 'nullable|string|url',
            'movie_ids' => 'array',
            'movie_ids.*' => 'nullable|integer|exists:movies,id'
        ];
    }

    private function syncWithMovie(): void
    {
        if (isset($this->parameters['movie_ids'])) {
            $this->model->movies()->sync($this->parameters['movie_ids']);
        }
    }
}
