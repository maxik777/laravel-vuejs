<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {

    for ($x = 0; $x<10; $x++) {
        return [
            'name' => $faker->name,
            'description' => Str::random(30),
        ];
    }
});
