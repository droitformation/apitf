<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategorieTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
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
        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create();

        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'numero'         => '4A_123/2017',
            "categorie_id"   =>  $categorie->id,
            "remarque"       =>  "Assurance-accidents 1",
            "publication_at" =>  "2018-01-03 00:00:00",
            "decision_at"    =>  "2017-12-05 00:00:00",
            "langue"         =>  0,
        ]);

        $decision2 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'numero'         => '4A_1345/2017',
            "categorie_id"   =>  $categorie->id,
            "remarque"       =>  "Assurance-accidents 2",
            "publication_at" =>  "2018-01-03 00:00:00",
            "decision_at"    =>  "2017-12-05 00:00:00",
            "langue"         =>  1,
        ]);

        $data = [
            [
                'id'             => $decision1->id,
                'numero'         => '4A_123/2017',
                "categorie_id"   =>  $categorie->id,
                "remarque"       =>  "Assurance-accidents 1",
                "publication_at" =>  "2018-01-03 00:00:00",
                "decision_at"    =>  "2017-12-05 00:00:00",
                "langue"         =>  0,
                "year"           =>  2018
            ],
            [
                'id'             => $decision2->id,
                'numero'         => '4A_1345/2017',
                "categorie_id"   =>  $categorie->id,
                "remarque"       =>  "Assurance-accidents 2",
                "publication_at" =>  "2018-01-03 00:00:00",
                "decision_at"    =>  "2017-12-05 00:00:00",
                "langue"         =>  1,
                "year"           =>  2018
            ]
        ];

        $response = $this->call('GET', 'api/categorie/'.$categorie->id);

        $response->assertStatus(200)->assertJson($data);
    }

    public function getSearchData()
    {
        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create();

        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'numero'         => '4A_123/2017',
            "categorie_id"   =>  $categorie->id,
            "remarque"       =>  "Assurance-accidents 1",
            "texte"          =>  '<p>Cindy Leschaud</p>',
            "publication_at" =>  "2018-01-03 00:00:00",
            "decision_at"    =>  "2017-12-05 00:00:00",
            "langue"         =>  0,
            "published"      =>  1,
        ]);

        $data = [
            "terms"  => "Cindy Leschaud",
            "period"  =>  [
                0 => '2018-01-02',
                1 => '2018-01-04'
            ],
            "categorie_id"  => $categorie->id,
            "published"     => 1
        ];

        $result = [
            [
                'id'             => $decision1->id,
                'numero'         => '4A_123/2017',
                "categorie_id"   =>  $categorie->id,
                "remarque"       =>  "Assurance-accidents 1",
                "publication_at" =>  "2018-01-03 00:00:00",
                "decision_at"    =>  "2017-12-05 00:00:00",
                "langue"         =>  0,
                "published"      =>  1,
                "year"           => 2018
            ]
        ];

        $response = $this->call('GET', 'api/search', $data);

        $response->assertStatus(200)->assertJson($result);
    }

    public function testMakeCategorieQuery()
    {
        $name = 'Droit pénal (en général)';

        $worker = \App::make('App\Droit\Categorie\Worker\CategorieWorkerInterface');

        $query = $worker->makeQuery($name);

        $string  = 'soundex(name)=soundex("'.$name.'")';
        $string .= ' OR soundex(name)=soundex("Droit pénal (général)")';
        $string .= ' OR soundex(name_de)=soundex("'.$name.'") OR soundex(name_it)=soundex("'.$name.'")';

        $this->assertEquals($string,$query);

        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create([
            'name' => $name,
            'name_de' => 'Das nichtgut (en général) de',
            'name_it' => 'El nobueno (en général) it'
        ]);

        $repo = \App::make('App\Droit\Categorie\Repo\CategorieInterface');

        $found = $repo->searchByName($name, 'testing');

    }
}
