<?php

namespace App\Omdb;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Psr\Http\Message\StreamInterface;

class OmdbApi
{
    private const API_KEY = 'c9d3739b';
    private const OMDB_API_BASE_URL = 'http://www.omdbapi.com/';
    private const TYPE_MOVIE = 'movie';
    private const METHOD_GET = 'GET';

    /**
     * @var Client
     */
    private Client $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * @param string $title
     * @param string|null $year
     * @return StreamInterface
     * @throws GuzzleException
     */
    public function findInOmdb(string $title, ?string $year): StreamInterface
    {
//        $title = Arr::get($data, 'title');
//        $year = Arr::get($data, 'year');

        $response = $this->guzzleClient->request(self::METHOD_GET, self::OMDB_API_BASE_URL, [
            'query' => [
                'apikey' => self::API_KEY,
                'type' => self::TYPE_MOVIE,
                't' => $title,
                'y' => $year
            ]
        ]);

        return $response->getBody();
    }
}
