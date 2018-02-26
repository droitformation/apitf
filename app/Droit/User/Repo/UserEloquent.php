<?php namespace  App\Droit\User\Repo;

use  App\Droit\User\Repo\UserInterface;
use  App\Droit\User\Entities\User as M;

class UserEloquent implements UserInterface{

    protected $user;

    public function __construct(M $user)
    {
        $this->user = $user;
    }

    public function getAll()
    {
        return $this->user->all();
    }

    public function find($id)
    {
        return $this->user->with(['abos','published'])->find($id);
    }

    public function getByCadence($cadence)
    {
        return $this->user->with(['abos','published'])
            ->where('cadence','=',$cadence)
            ->whereDate('active_until', '>', \Carbon\Carbon::today()->startOfDay())
            ->get();
    }
    
    public function create(array $data)
    {
        $user = $this->user->create(array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ));

        if( ! $user ){
            return false;
        }

        return $user;
    }

    public function update(array $data){

        $user = $this->user->findOrFail($data['id']);

        if( ! $user )
        {
            return false;
        }

        $user->fill($data);

        if(isset($data['password']) && !empty($data['password'])){
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return $user;
    }

    public function delete($id){

        $user = $this->user->find($id);

        return $user->delete();
    }
}
