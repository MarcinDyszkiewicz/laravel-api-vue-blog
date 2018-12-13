<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'body' => $faker->paragraph(8),
        'image' => $faker->imageUrl(),
        'meta_title' => $faker->title,
        'meta_description' => $faker->sentence(6),
        'summary' => $faker->paragraph(1),
        'slug' => $faker->url
    ];
});