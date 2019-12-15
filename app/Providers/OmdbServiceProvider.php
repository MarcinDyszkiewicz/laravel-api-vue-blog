<?php

namespace App\Providers;

use App\Omdb\Omdb;
use App\Omdb\OmdbApi;
use Illuminate\Support\ServiceProvider;

class OmdbServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OmdbApi::class);
        $this->app->singleton(Omdb::class, function ($app) {
            return new Omdb(new OmdbApi());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
