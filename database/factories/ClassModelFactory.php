<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(ClassModel::class, function (Faker $faker) {
    return [
        'subject_id' => $faker->name,
        'section' => $faker->word,
        'teacher' => $faker->name,
    ];
});
