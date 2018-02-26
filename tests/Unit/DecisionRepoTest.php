<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DecisionRepoTest extends TestCase
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

    public function testModelAlredyExistInDb()
    {
        $date = \Carbon\Carbon::today();

        $decision = factory(\App\Droit\Decision\Entities\Decision::class)->create(['numero' => '4A_123/2017','publication_at' => $date]);

        $repo = \App::make('App\Droit\Decision\Repo\DecisionInterface');

        $results = $repo->findByNumeroAndDate('4A_123/2017',$date);

        $this->assertSame($results->numero,$decision->numero);
    }

    public function testGetDatesMissing()
    {
        $today = \Carbon\Carbon::today();

        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create(['numero' => '4A_123/2017','publication_at' => $today]);
        $decision2 = factory(\App\Droit\Decision\Entities\Decision::class)->create(['numero' => '4A_123/2017','publication_at' => $today->addDay(1)]);

        $repo = \App::make('App\Droit\Decision\Repo\DecisionInterface');

        $results = $repo->getMissingDates([$today->addDay(1),$today,$today->addDays(2)]);

        $this->assertEquals(1,$results->count());
    }

    public function testSearchParams()
    {
        $date = \Carbon\Carbon::today()->toDateTimeString();

        $decision = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' =>
                '<div>Dapibus ante accumasa laoreelentesque lorém arcû in quisqué éuismod metus enim imperdiet egéstat ligula àc voluptà torquent sapien 
                placérat liçlà à, nullä ultrices Assurance de Protection Juridique SA égét 44 395€ dapidûs quisque à nullä dui congue ïpsum séd léo séd hac.
                Conges quém mattis sènèctus malet, consequat liçlà</div>.

                <div>Morbi phasellus c\'est torquenté malésdum aptenté l\'2 068€ duis sem fancibüs classé d\'adipiscing duis, rutrum malésdum elementum mi est 
                velit faucibus élémentum interûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit 
                çunc, primiés?</div>',
        ]);

        $repo = \App::make('App\Droit\Decision\Repo\DecisionInterface');

        // Pass only array of terms
        $params['terms'] = ['Assurance de Protection Juridique SA'];

        $results = $repo->search($params);

        $this->assertTrue($results->contains('id',$decision->id));

        // With date
        $params['publication_at'] = $date;

        $results = $repo->search($params);

        $this->assertTrue($results->contains('id',$decision->id));

        // With all the above and categorie
        $params['categorie_id'] = $decision->categorie_id;

        $results = $repo->search($params);

        $this->assertTrue($results->contains('id',$decision->id));

        // With all the above and published but isn't
        $params['published'] = 1;

        $results = $repo->search($params);

        $this->assertFalse($results->contains('id',$decision->id));
    }

    public function testSearchString()
    {
        $date = \Carbon\Carbon::today()->toDateTimeString();

        $decision = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' =>
                '<div>Dapibus ante accumasa laoreelentesque lorém arcû in quisqué éuismod metus enim imperdiet egéstat ligula àc voluptà torquent sapien 
                placérat liçlà à, nullä ultrices Assurance de Protection Juridique SA égét 44 395€ dapidûs quisque à nullä dui congue ïpsum séd léo séd hac.
                Conges quém mattis sènèctus malet, consequat liçlà</div>.

                <div>Morbi phasellus c\'est torquenté malésdum aptenté l\'2 068€ duis sem fancibüs classé d\'adipiscing duis, rutrum malésdum elementum mi est 
                velit faucibus élémentum interûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit 
                çunc, primiés?</div>',
        ]);

        $decision2 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul Assurance de Protection Juridique SA égét 44 3€ dapidûs quisque à nullä dui cctus malet, consequat liçlà</div>.'
        ]);

        $repo = \App::make('App\Droit\Decision\Repo\DecisionInterface');

        // Pass only array of terms
        $params['terms'] = ['Assurance de Protection Juridique SA','élémentum interûllam çurcus molestié','vestibulum'];

        $results = $repo->search($params);

        // We found only the first one, it contains all keywords
        $this->assertTrue($results->contains('id',$decision->id));
        $this->assertEquals(1,$results->count());
    }

    public function testSearchKeywordInNewDecisisons()
    {
        $date = \Carbon\Carbon::today()->startOfDay()->toDateTimeString();

        // Control
        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus ante accumasa laoreelentesque lorém arcû in quisqué éuismod m44equat liçlà</div>.'
        ]);

        // BGFA => categorie 244
        $decision2 =  factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul A égét 44 3€ BGFA quisque à nullä dui cctus malet, consequat liçlà</div>.'
        ]);

        // LLCA => categorie 244
        $decision3 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul de LLCA de chose égét 44 3€ quisque à nullä dui cctus malet, consequat liçlà</div>.'
        ]);

        // "Assistance judiciaire", CPC => categorie 207
        $decision4 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul de Assistance judiciaire égét 44 3€ quisque à nullä dui cctus CPC, consequat liçlà</div>.'
        ]);

        $worker = \App::make('App\Droit\Categorie\Worker\CategorieWorkerInterface');

        $results = $worker->find($date); // $date => publication_at

        // We found decisison for both categories for the date
        $this->assertTrue(!$results->get(244)->isEmpty());
        $this->assertTrue(!$results->get(207)->isEmpty());

        $this->assertEquals(2,$results->get(244)->count());
        $this->assertEquals(1,$results->get(207)->count());
    }

    public function testAttachOtherCategorieForDecision()
    {
        $date = \Carbon\Carbon::today()->startOfDay()->toDateTimeString();

        // Control
        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus ante accumasa laoreelentesque lorém arcû in quisqué éuismod m44equat liçlà</div>.'
        ]);

        // BGFA => categorie 244
        $decision2 =  factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul A égét 44 3€ BGFA quisque à nullä dui cctus malet, consequat liçlà</div>.'
        ]);

        // LLCA => categorie 244
        $decision3 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul de LLCA de chose égét 44 3€ quisque à nullä dui cctus malet, consequat liçlà</div>.'
        ]);

        // "Assistance judiciaire", CPC => categorie 207
        $decision4 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus à nul de Assistance judiciaire égét 44 3€ quisque à nullä dui cctus CPC, consequat liçlà</div>.'
        ]);

        $worker = \App::make('App\Droit\Categorie\Worker\CategorieWorkerInterface');

        $worker->process($date); // $date => publication_at

        $decision1->fresh();
        $decision2->fresh();
        $decision3->fresh();
        $decision4->fresh();

        $this->assertFalse($decision1->other_categories->contains('id',244));
        $this->assertFalse($decision1->other_categories->contains('id',207));

        $this->assertTrue($decision2->other_categories->contains('id',244));
        $this->assertTrue($decision3->other_categories->contains('id',244));
        $this->assertTrue($decision4->other_categories->contains('id',207));
    }

    public function testOtherCategorieForDecisionNotFound()
    {
        $date = \Carbon\Carbon::today()->startOfDay()->toDateTimeString();

        // Control
        $decision1 = factory(\App\Droit\Decision\Entities\Decision::class)->create([
            'publication_at' => $date,
            'texte' => '<div>Dapibus ante accumasa laoreelentesque lorém arcû in quisqué éuismod m44equat liçlà</div>.'
        ]);

        $worker = \App::make('App\Droit\Categorie\Worker\CategorieWorkerInterface');

        $worker->process($date); // $date => publication_at

        $decision1->fresh();

        $this->assertFalse($decision1->other_categories->contains('id',244));
        $this->assertFalse($decision1->other_categories->contains('id',207));
    }
}
