<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AboTest extends TestCase
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

    public function testMakeAbo()
    {
        $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $data = [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => ['Lorem ipsum dolor','Ispum dolor amet']
        ];

        $worker->make($data);

        $this->assertDatabaseHas('abos', [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => 'Lorem ipsum dolor'
        ]);

        $this->assertDatabaseHas('abos', [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => 'Ispum dolor amet'
        ]);
    }

    public function testRemoveAbo()
    {
        $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $data = [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => ['Lorem ipsum dolor']
        ];

        $worker->make($data);

        $data = ['user_id' => $user->id, 'categorie_id' => 199];

        $worker->remove($data);

        $this->assertDatabaseMissing('abos', [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => 'Lorem ipsum dolor'
        ]);
    }

    public function testMakeAboUrl()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $data = [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => ['Lorem ipsum dolor','Ispum dolor amet']
        ];

        $response = $this->call('POST', 'api/abo/make', $data);

        $this->assertDatabaseHas('abos', [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => 'Lorem ipsum dolor'
        ]);

        $data = ['user_id' => $user->id, 'categorie_id' => 199];

        $response = $this->call('POST', 'api/abo/remove', $data);

        $this->assertDatabaseMissing('abos', [
            'user_id'      => $user->id,
            'categorie_id' => 199,
            'keywords'     => 'Lorem ipsum dolor'
        ]);
    }


}
