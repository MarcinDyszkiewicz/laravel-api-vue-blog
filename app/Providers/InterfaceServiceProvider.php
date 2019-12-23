<?php

namespace App\Providers;

use App\Repositories\Movie\MovieRepository;
use App\Repositories\Movie\MovieRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
    }
}
