<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Students;
use Faker\Generator as Faker;

$factory->define(Students::class, function (Faker $faker) {
    return [
        'std_name' => $faker->name,
        'std_address' => $faker->address,
        'std_contactno' => $faker->phoneNumber,
    ];
});
