<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Subjects;
use Faker\Generator as Faker;

$factory->define(Subjects::class, function (Faker $faker) {
    return [
        'subject_name' => $faker->word,
    ];
});
