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

$factory->define(App\Droit\Categorie\Entities\Categorie::class, function (Faker $faker) {
    return [
        'name'      => 'Ma categorie',
        'name_de'   => 'Ma categorie all',
        'name_it'   => 'Ma categorie it',
        'parent_id' => 0,
        'rang'      => 0,
        'general'   => null,
    ];
});

$factory->define(App\Droit\Abo\Entities\Abo::class, function (Faker $faker) {
    return [
        'user_id'      => 1,
        'categorie_id' => 1,
        'keywords'     => 'words',
    ];
});

$factory->define(App\Droit\Transfert\Site\Entities\Site::class, function (Faker $faker) {
    return [
        'nom'    => $faker->word,
        'url'    => $faker->url,
        'logo'   => $faker->word,
        'slug'   => $faker->word,
        'prefix' => $faker->word
    ];
});



/**
 * Newsletter
 */
$factory->define(App\Droit\Transfert\Newsletter\Entities\Newsletter::class, function (Faker $faker) {
    return [
        'titre'        => $faker->sentence,
        'from_name'    => $faker->name,
        'from_email'   => $faker->email,
        'return_email' => $faker->email,
        'unsuscribe'   => $faker->word,
        'preview'      => $faker->word,
        'site_id'      => null,
        'list_id'      => $faker->numberBetween(100,3000),
        'color'        => $faker->colorName,
        'logos'        => $faker->word.'.png',
        'header'       => $faker->word.'.png',
        'soutien'      => null
    ];
});

$factory->define(App\Droit\Transfert\Newsletter\Entities\Newsletter_users::class, function (Faker $faker) {
    return [
        'email'        => $faker->email,
        'activation_token' => '1234',
        'activated_at' => date('Y-m-d G:i:s')
    ];
});

$factory->define(App\Droit\Transfert\Newsletter\Entities\Newsletter_subscriptions::class, function (Faker $faker) {
    return [
        'user_id'       => 1,
        'newsletter_id' => 1
    ];
});

$factory->define(App\Droit\Transfert\Newsletter\Entities\Newsletter_campagnes::class, function (Faker $faker) {
    return [
        'sujet'           => 'Sujet',
        'auteurs'         => 'Cindy Leschaud',
        'status'          => 'Brouillon',
        'newsletter_id'   => 1,
        'api_campagne_id' => 1,
        'send_at'         => null,
        'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
        'updated_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
    ];
});

$factory->define(App\Droit\Transfert\Newsletter\Entities\Newsletter_contents::class, function (Faker $faker) {
    return [
        'type_id'       => 6, // text
        'titre'        => null,
        'contenu'      => null,
        'image'        => null,
        'lien'         => null,
        'arret_id'     => null,
        'categorie_id' => null,
        'newsletter_campagne_id' => 1,
        'rang'      => 1,
        'groupe_id' => null,
    ];
});

$factory->define(App\Droit\Transfert\Arret\Entities\Arret::class, function (Faker $faker) {
    return [
        'reference'  => 'reference 123',
        'pub_date'   => \Carbon\Carbon::now(),
        'abstract'   => 'lorem ipsum dolor amet',
        'pub_text'   => 'amet dolor ipsum lorem'
    ];
});

$factory->define(App\Droit\Transfert\Arret\Entities\Groupe::class, function (Faker $faker) {
    return [
        'categorie_id' => 39
    ];
});

$factory->define(App\Droit\Transfert\Categorie\Entities\Categorie::class, function (Faker $faker) {
    return [
        'title'   => $faker->sentence,
        'image'   => 'lorex.jpg',
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