<?php

namespace App\Factories\Person;

use Illuminate\Database\Eloquent\Model;

interface PersonFactoryContract
{
    /**
     * @return Model
     */
    public function create(): Model;

    public function attachToMovie(): void;

    public function validate(): bool;
}
