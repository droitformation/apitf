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
        $user =  $this->user->find($id);

        if(!$user){
            $user = $this->user->create([
                'id'    => $data['ID'],
                'name'     => $data['user_login'],
                'email'    => $data['user_email'],
                'cadence'  => 'weekly',
                'password' => bcrypt($data['user_pass']),
            ]);
        }

        return $user;
    }
}