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

$factory->define(App\Droit\User\Entities\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'active_until' => null,
        'cadence' => null,
    ];
});

$factory->define(App\Droit\Decision\Entities\Decision::class, function (Faker $faker) {
    return [
        'publication_at' => $faker->dateTime,
        'decision_at'    => $faker->dateTime,
        'categorie_id'   => 1,
        'remarque'       => $faker->word,
        'numero'         => '3A_23/2017',
        'link'           => $faker->url,
        'texte'          => $faker->text(200),
        'langue'         => 1,
        'publish'        => null,
        'updated'        => null,
        'created_at'      => \Carbon\Carbon::now(),
        'updated_at'      => \Carbon\Carbon::now()
    ];
});

$categories = \App\Droit\Categorie\Entities\Categorie::all();

$factory->define(App\Droit\Abo\Entities\Abo::class, function (Faker $faker) use ($categories) {

    $categorie = $categories->random();

    return [
        'user_id'       => $faker->numberBetween(1,10),
        'categorie_id'  => $categorie->id,
        'keywords'      => $faker->word,
    ];
});