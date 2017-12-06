<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\Fomvasss\Taxonomy\Models\Vocabulary::class, function (Faker $faker) {

    return [
        'name' => $faker->state,
        'body' => $faker->paragraph(),
    ];
});

$factory->define(\Fomvasss\Taxonomy\Models\Term::class, function (Faker $faker) {

    return [
        'name' => $faker->city,
        'body' => $faker->paragraph(),
        'vocabulary_id' => \Fomvasss\Taxonomy\Models\Vocabulary::inRandomOrder()->first()->id
    ];
});
