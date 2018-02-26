<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlertTest extends TestCase
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

    public function testGetDates()
    {
        $today  = \Carbon\Carbon::today();
        $monday = $today->startOfWeek();
        $friday = $today->startOfWeek()->parse('this friday');

        $publication_at = generateDateRange($monday, $friday);

        $alert = \App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence('daily')->setDate($publication_at);

        $date = $alert->getDate();

        $this->assertEquals($friday,$date);
    }

    public function testGetUsersNotAlreadySent()
    {
        $publication_at = \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString();

        $abo = $this->makeDecisionAndAbos($publication_at);

        $alert = \App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence('daily')->setDate($publication_at);

        // Mark it sent
        $alert->sent($abo);

        $users = $alert->getUsers();

        $this->assertEquals(0,$users->count());
    }

    /* =================================
     * Make decisions and user
     =================================== */
    public function makeDecisionAndAbos($publication_at)
    {
        $data = [
            ['categorie_id' => 174, 'texte' => '<div>Accumasa laoreelentesque lorém arcû in quisqué éuismod m44equat liçlà</div>.'],
            ['categorie_id' => 175, 'texte' => '<div>Dapibus à nul A égét 44 3€ BGFA quisque à nullä dui cctus malet, consequat liçlà</div>.'],
            ['categorie_id' => 176, 'texte' => '<div>Nul de chose égét 44 3€ quisque à nullä dui cctus malet, consequatà</div>.'],
            ['categorie_id' => 177, 'texte' => '<div>Judiciaire égét quisque à nullä dui cctus , consequat liçlà</div>.']
        ];

        $make = new \tests\factories\ObjectFactory();
        $decisions = $make->makeDecisions($publication_at,$data);

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $abo1 = factory(\App\Droit\Abo\Entities\Abo::class)->create(['user_id'  => $user->id, 'categorie_id' => 174, 'keywords' => '"Accumasa laoreelentesque"']);
        $abo2 = factory(\App\Droit\Abo\Entities\Abo::class)->create(['user_id'  => $user->id, 'categorie_id' => 175, 'keywords' => '"à nul A égét 44",BGFA']);
        $abo3 = factory(\App\Droit\Abo\Entities\Abo::class)->create(['user_id'  => $user->id, 'categorie_id' => 176, 'keywords' => null]);

        return $user;
    }
}
