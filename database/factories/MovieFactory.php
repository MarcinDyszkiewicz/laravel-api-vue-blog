<?php

use Faker\Generator as Faker;

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
