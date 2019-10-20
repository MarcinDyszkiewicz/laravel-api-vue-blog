<?php

namespace App\Managers\Person;

use App\Factories\Person\PersonFactory;
use App\Models\Actor;
use App\Models\Director;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\Model;

class PersonManager
{
    /**
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createActor(array $data): Model
    {
        try {
            $personFactory = new PersonFactory(new Actor(), $data);

            return $personFactory->create();
        } catch (ValidationException $exception) {
            abort(400, $exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createDirector(array $data): Model
    {
        $personFactory = new PersonFactory(new Director(), $data);

        return $personFactory->create();
    }

    /**
     * @param Model $model
     * @param array $data
     * @return Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePerson(Model $model, array $data): Model
    {
        $personFactory = new PersonFactory($model, $data);

        return $personFactory->update();
    }
}
