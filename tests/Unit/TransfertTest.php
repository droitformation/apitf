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


}
