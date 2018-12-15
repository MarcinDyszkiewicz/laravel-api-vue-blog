<?php

namespace App\Services;

use App\Movie;
use GuzzleHttp\Client;

class MovieService
{
    /**
     * @param $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findInOmdb($data)
    {
        $title = array_get($data, 'title');
        $year = array_get($data, 'year');

        $GuzzleClient = new Client();
        $movie = $GuzzleClient->request('GET', 'http://www.omdbapi.com/?apikey=c9d3739b&', [
            'query' => [
                'apikey' => 'c9d3739b',
                't' => $title,
                'y' => $year
            ]
        ])->getBody();

        return $movie;
    }

    public function createMovie()
    {

    }
}