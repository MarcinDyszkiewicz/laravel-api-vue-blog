<?php

namespace App\Services;

use App\Models\Director;
use Illuminate\Support\Arr;

class DirectorService
{
    /**
     * @param $data
     * @return mixed
     */
    public function createDirector($data)
    {
        $director = Director::create([
            'full_name' => Arr::get($data, 'fullName'),
            'poster' => Arr::get($data, 'poster'),
        ]);
        $movieIds = Arr::get($data, 'movieIds');
        if (!empty($movieIds)) {
            $director->movies()->attach(array_wrap($movieIds));
        }

        return $director;
    }

    /**
     * @param $data
     * @param Director $director
     * @return Director
     */
    public function updateDirector($data, Director $director)
    {
        $director->update([
            'full_name' => Arr::get($data, 'fullName'),
            'poster' => Arr::get($data, 'poster')
        ]);
        $movieIds = Arr::get($data, 'movieIds');
        if (!empty($movieIds)) {
            $director->movies()->sync(array_wrap($movieIds));
        }

        return $director;
    }
}
