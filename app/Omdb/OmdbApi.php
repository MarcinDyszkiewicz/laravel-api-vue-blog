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
        $this->guzzleClient = $this->createClient();
    }

    /**
     * @param  string  $title
     * @param  string|null  $year
     * @return StreamInterface
     */
    public function findInOmdbByTitle(string $title, ?string $year): StreamInterface
    {
        $response = $this->guzzleClient->get(null, [
            'query' => [
                't' => $title,
                'y' => $year
            ]
        ]);

        return $response->getBody();
    }

    /**
     * @param  string  $id
     * @return array
     */
    public function findInOmdbById(string $id): array
    {
        $response = $this->guzzleClient->get(self::OMDB_API_BASE_URL, [
            'query' => [
                'apikey' => self::API_KEY,
                'i' => $id,
            ]
        ]);
        $body = $response->getBody();

        return json_decode($body, true);
    }


    /**
     * @param  string  $title
     * @param  string|null  $year
     * @return array|null
     */
    public function searchInOmdb(string $title, ?string $year): ?array
    {
        $response = $this->guzzleClient->get(self::OMDB_API_BASE_URL, [
            'query' => [
                'apikey' => self::API_KEY,
                'type' => self::TYPE_MOVIE,
                's' => $title,
                'y' => $year
            ]
        ]);
        $body = $response->getBody();
        $decodedBody = json_decode($body, true);

        if ($decodedBody['Response'] === "False") {
            return [];
        }

        return $decodedBody['Search'];
    }

    /**
     * @return Client
     */
    private function createClient()
    {
        return new Client([
            'base_url' => self::OMDB_API_BASE_URL,
            'defaults' => [
                'query' => [
                    'apikey' => self::API_KEY,
                    'type' => self::TYPE_MOVIE,
                ]
            ]
        ]);
    }
}
