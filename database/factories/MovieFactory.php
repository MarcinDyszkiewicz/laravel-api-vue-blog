<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(App\Models\Movie::class, function (Faker $faker) {
    return [
        'title' => $faker->firstName,
        'year' => $faker->year,
        'released' => $faker->date(),
        'runtime' => $faker->randomNumber(3),
        'plot' => $faker->paragraph(2),
        'review' => $faker->paragraph(3),
        'poster' => $faker->imageUrl(),
        'internet_movie_database_rating' => $faker->randomDigit,
        'rotten_tomatoes_rating' => $faker->randomDigit,
        'metacritic_rating' => $faker->randomDigit,
        'imdb_rating' => $faker->randomDigit,
        'slug' => $faker->slug,
    ];
});

$factory->state(App\Models\Movie::class, 'batman', [
    'title' => 'Batman',
    'year' => 1989,
    'released' => Carbon::createFromDate(1989, 06, 23),
    'runtime' => 126,
    'plot' => 'The Dark Knight of Gotham City begins his war on crime with his first major enemy being the 
            clownishly homicidal Joker.',
    'review' => null,
    'poster' => 'https://m.media-amazon.com/images/M/MV5BMTYwNjAyODIyMF5BMl5BanBnXkFtZTYwNDMwMDk2._V1_SX300.jpg	',
    'internet_movie_database_rating' => null,
    'rotten_tomatoes_rating' => null,
    'metacritic_rating' => null,
    'imdb_rating' => null,
    'slug' => 'batman-1989',
    'imdb_id' => 'tt0096895'
]);
