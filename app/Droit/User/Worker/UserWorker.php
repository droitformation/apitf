<?php namespace App\Droit\User\Worker;

use App\Droit\User\Worker\UserWorkerInterface;
use App\Droit\User\Repo\UserInterface;

class UserWorker implements UserWorkerInterface
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function find($id, $data)
    {
        $cadence = isset($data['cadence']) ? $data['cadence'] : 'weekly';

         return $this->user->makeOrUpdate([
            'id'       => $id,
            'name'     => $data['user_login'],
            'email'    => $data['user_email'],
            'active_until' => isset($data['active_until']) ? $data['active_until'] : null,
            'cadence'  => $cadence == 'all' || $cadence == 'weekly' ? 'weekly' : 'daily',
            'password' => bcrypt($data['user_pass']),
        ]);
    }
}