<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DecisionApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testGetDetailDecision()
    {
        /*
         * Keep year current to touch mysql database
         * Else the table does not exist
         * */
        $decision = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'numero'         => '4A_123/2017',
            'publication_at' => '2019-02-03 00:00:00',
            'decision_at'    => '2019-02-03 00:00:00',
            "categorie_id"   => 199,
            "remarque"       => "Assurance-accidents",
            "link"           =>  "http => //relevancy.bger.ch…oom=&type=show_document",
            "texte"          =>  "<h1>9C_90/2018 06.02.201…reiberin : Oswald </div>",
            "langue"         =>  1,
            "publish"        =>  0,
            "updated"        =>  NULL,
            "created_at"     =>  "2019-02-28 07:41:04",
            "updated_at"     =>  "2019-02-28 07:41:04"
        ]);

        $response = $this->call('GET', 'api/decision/'.$decision->id.'/2019');

        $response->assertStatus(200)
            ->assertJson([
                'id'             => $decision->id,
                'numero'         => '4A_123/2017',
                'publication_at' => '2019-02-03 00:00:00',
                'decision_at'    => '2019-02-03 00:00:00',
                "categorie_id"   => 199,
                "remarque"       => "Assurance-accidents",
                "link"           =>  "http => //relevancy.bger.ch…oom=&type=show_document",
                "texte"          =>  "<h1>9C_90/2018 06.02.201…reiberin : Oswald </div>",
                "langue"         =>  1,
                "publish"        =>  0,
                "created_at"     =>  "2019-02-28 07:41:04",
                "updated_at"     =>  "2019-02-28 07:41:04"
        ]);
    }

}
