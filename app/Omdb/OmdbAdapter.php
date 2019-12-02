<?php

namespace App\Omdb;

use App\Omdb\Models\OmdbMovie;

class OmdbAdapter
{
    /**
     * @var OmdbMovie
     */
    private OmdbMovie $omdbMovie;

    /**
     * OmdbAdapter constructor.
     * @param OmdbMovie $omdbMovie
     */
    public function __construct(OmdbMovie $omdbMovie)
    {

        $this->omdbMovie = $omdbMovie;
    }
}
