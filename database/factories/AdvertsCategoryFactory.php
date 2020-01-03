<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Adverts\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'slug' => $faker->unique()->slug(2),
        'parent_id' => null,
    ];
});
