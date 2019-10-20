<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\ActorMovie;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class RatePersonService
{
    /**
     * @var int
     */
    private $rate;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var int
     */
    private $userId;

    /**
     * RatePersonService constructor.
     * @param int $rate
     * @param Model $model
     * @param int $userId
     */
    public function __construct(int $rate, Model $model, int $userId)
    {
        $this->rate = $rate;
        $this->model = $model;
        $this->userId = $userId;
    }

    /**
     * @return Rating|null
     * @throws \Exception
     */
    public function ratePerson(): ?Rating
    {
        if ($this->userAlreadyRated()) {
            throw new \Exception('You have already rated this actor');

        }

        return $this->createRating();
    }

    /**
     * @param int $movieId
     * @return Rating|null
     * @throws \Exception
     */
    public function rateActorForMovie(int $movieId): ?Rating
    {
        if (!$this->actorPlayedInMovie($movieId)) {
            throw new \Exception('Actor haven\'t played in this movie');
        }

        return $this->ratePerson();
    }

    /**
     * @return bool
     */
    private function userAlreadyRated(): bool
    {
        return $this->model->ratings()->where('user_id', $this->userId)->exists();
    }

    /**
     * @return Rating
     */
    private function createRating(): Rating
    {
        return $this->model->ratings()->create([
            'user_id' => $this->userId,
            'rate' => $this->rate
        ]);
    }

    /**
     * @param $movieId
     * @return bool
     */
    private function actorPlayedInMovie($movieId): bool
    {
        return $this->model->movies()->where('movie_id', $movieId)->exists();
    }
}
