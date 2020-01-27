<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Domain\Model\Photo;
use Faker\Generator as Faker;

$factory->define(Photo::class, function (Faker $faker) {
    return [
        'picture_id' => $faker->randomNumber(),
        'image_source' => $faker->imageUrl(),
        'user_id' => function () {
            return factory(User::class)->make()->id;
        }
    ];
});
