<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAboTest extends TestCase
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

    public function testGetUsersForCadence()
    {
        $repo = \App::make('App\Droit\User\Repo\UserInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $users = $repo->getByCadence('daily');

        $this->assertEquals(1,$users->count());
    }

    public function testGetUsersForCadenceNoUsers()
    {
        $repo = \App::make('App\Droit\User\Repo\UserInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'weekly',
        ]);

        $users = $repo->getByCadence('daily');

        $this->assertEquals(0,$users->count());
    }

    public function testGetUsersForCadenceNoActiveUsers()
    {
        $repo = \App::make('App\Droit\User\Repo\UserInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->subMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $users = $repo->getByCadence('daily');
        $all = $repo->getAll();

        $this->assertEquals(0,$users->count());
        $this->assertEquals(1,$all->count());
    }

    public function testGetAbosOfUser()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $abo1 = factory(\App\Droit\Abo\Entities\Abo::class)->create([
            'user_id'  => $user->id,
            'keywords' => '"Assurance de Protection Juridique SA"',
        ]);

        $abo2 = factory(\App\Droit\Abo\Entities\Abo::class)->create([
            'user_id'  => $user->id,
            'keywords' => '"recours en matière civile","canton de Genève"',
        ]);

        $expect = collect([
            $abo1->categorie_id => ['keywords' => collect([collect(['Assurance de Protection Juridique SA'])]), 'published' => false],
            $abo2->categorie_id => ['keywords' => collect([collect(['recours en matière civile','canton de Genève'])]), 'published' => false],
        ]);

        $this->assertEquals($expect,$user->abonnements);
    }

    public function testGetUsers()
    {
        $publication_at = \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString();

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

        $this->assertTrue($user->abonnements->keys()->contains(174));
        $this->assertTrue($user->abonnements->keys()->contains(175));

        $alert  = \App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence('daily')->setDate($publication_at);
        $users = $alert->getUsers();

        $this->assertEquals(1,$users->count());
        $this->assertEquals($user->name,$users->first()['user']->name);
        $this->assertEquals(3,$users->first()['abos']->count());

        $alert->setCadence('weekly')->setDate($publication_at);
        $users = $alert->getUsers();

        $this->assertEquals(0,$users->count());
    }
}
