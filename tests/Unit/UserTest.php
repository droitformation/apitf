<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
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

    public function testSetCadence()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'active_until' => \Carbon\Carbon::today()->startOfDay()->addMonth()->toDateTimeString(),
            'cadence'      => 'daily',
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'cadence' => 'daily',
        ]);

        $data = [
            'user_id' => $user->id,
            'cadence' => 'weekly',
        ];

        $response = $this->call('POST', 'api/abo/cadence', $data);

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'cadence' => 'weekly',
        ]);
    }

    public function testGetOoMakeUser()
    {
        $data = [
            'id' => '1',
            'data' => [
                'ID' => '1',
                'user_login'    => 'cleschaud',
                'user_pass'     => '',
                'user_nicename' => 'cleschaud',
                'user_email' => 'cindy.leschaud@gmail.com',
                'user_url'   => '',
                'user_registered' => '2017-08-09 12:52:14',
                'user_activation_key' => '',
                'user_status' => '0',
            ],
        ];

        $this->assertDatabaseMissing('users', [
            'id'      => 1,
            'name'    => 'cleschaud',
            'email'   => 'cindy.leschaud@gmail.com',
            'cadence' => 'weekly',
        ]);

        $response = $this->call('POST', 'api/user', $data);

        $this->assertDatabaseHas('users', [
            'id'      => 1,
            'name'    => 'cleschaud',
            'email'   => 'cindy.leschaud@gmail.com',
            'cadence' => 'weekly',
        ]);

        $response->assertStatus(200)
            ->assertJson(['id' => '1', 'cadence' => 'weekly', 'abonnements' => [],
        ]);

    }

}
