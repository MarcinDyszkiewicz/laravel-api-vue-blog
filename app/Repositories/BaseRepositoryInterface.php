<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function create(): Model;

    public function update(): Model;

    public function validate(): bool;
}
