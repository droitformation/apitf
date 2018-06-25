<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Foundation\Testing\WithFaker;

class TransfertTest extends TestCase
{
    use ResetTbl,WithFaker;

    public function setUp(){
        parent::setUp();

        $this->app['config']->set('database.default','testing_transfert');
        $this->reset_all();
    }

    public function tearDown(){
        \Mockery::close();
        $this->reset_all();
        parent::tearDown();
    }

    public function testCreateSite()
    {
        $transfert = new \App\Droit\Transfert\Transfert();
        $transfert->makeSite([
            'nom'    => $this->faker->word,
            'url'    => $this->faker->url,
            'logo'   => $this->faker->word,
            'slug'   => $this->faker->word,
            'prefix' => $this->faker->word
        ]);

        $this->assertInstanceOf('App\Droit\Transfert\Site\Entities\Site',$transfert->site);
    }

    public function testCreateNewsletter()
    {
        $data = [
            'nom'    => $this->faker->word,
            'url'    => $this->faker->url,
            'logo'   => $this->faker->word,
            'slug'   => $this->faker->word,
            'prefix' => $this->faker->word
        ];

        $transfert = new \App\Droit\Transfert\Transfert();

        $model = $transfert->getOld('Newsletter');
        $data = [
            'titre'        => $this->faker->sentence,
            'from_name'    => $this->faker->name,
            'from_email'   =>'cindy.leschaud@gmail.com',
            'return_email' => $this->faker->email,
            'unsuscribe'   => $this->faker->word,
            'preview'      => $this->faker->word,
            'site_id'      => null,
            'list_id'      => $this->faker->numberBetween(100,3000),
            'color'        => $this->faker->colorName,
            'logos'        => $this->faker->word.'.png',
            'header'       => $this->faker->word.'.png',
            'soutien'      => null
        ];
        $model->fill($data);
        $model->save();

        $transfert->makeSite($data)->makeNewsletter($model);

        $this->assertInstanceOf('App\Droit\Transfert\Newsletter\Entities\Newsletter',$transfert->newsletter);

        $this->assertEquals($transfert->newsletter->from_email,'cindy.leschaud@gmail.com');
    }

    public function testTranfertTonewGroup()
    {
        $site = factory(\App\Droit\Transfert\Site\Entities\Site::class)->create();
        $categories = factory(\App\Droit\Transfert\Categorie\Entities\Categorie::class,3)->create();
        $arret = factory(\App\Droit\Transfert\Arret\Entities\Arret::class)->create();
        $newsletter = factory(\App\Droit\Transfert\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);
        $campagne = factory(\App\Droit\Transfert\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id,]);
        $groupe = factory(\App\Droit\Transfert\Arret\Entities\Groupe::class)->create(['categorie_id' => $categories->first()->id,]);

        $groupe->arrets()->attach([$arret->id]);

        $content = factory(\App\Droit\Transfert\Newsletter\Entities\Newsletter_contents::class)->create([
            'newsletter_campagne_id' => $campagne->id,
            'categorie_id'  => $categories->first()->id,
            'groupe_id'     => $groupe->id,
        ]);

        $campagne = $campagne->load('content')->fresh();

        $transfert = new \App\Droit\Transfert\Transfert();
        $transfert->conversions['Categorie']['table'][$categories->first()->id] = 234;
        $transfert->conversions['Arret']['table'][$arret->id] = 123;

        $new = $transfert->makeGroupe($content);

        $this->assertEquals(234, $new->categorie_id);

    }

    public function testMakeNewsletter()
    {
  /*      $newsletter = factory(\App\Droit\Transfert\Newsletter\Entities\Newsletter::class)->create(['site_id' => null]);

        $data = [
            'nom'    => $faker->word,
            'url'    => $faker->url,
            'logo'   => $faker->word,
            'slug'   => $faker->word,
            'prefix' => $faker->word
        ];

        $transfert = new \App\Droit\Transfert\Transfert();

        $transfert->makeSite($data)->makeNewsletter($newsletter);

        $this->assertInstanceOf('App\Droit\Transfert\Newsletter\Entities\Newsletter',$transfert->newsletter);*/

    }
}
