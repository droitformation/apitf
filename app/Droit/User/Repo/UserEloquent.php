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
        return $this->user->orderBy('name')->get();
    }

    public function find($id)
    {
        return $this->user->with(['abos','published'])->find($id);
    }

    public function getByCadence($cadence, $exclude = [])
    {
        return $this->user->has('abos')->with(['abos','published'])
            ->where('cadence','=',$cadence)
            ->whereDate('active_until', '>', \Carbon\Carbon::today()->startOfDay())
            ->exclude($exclude)
            ->get();
    }
    
    public function create(array $data)
    {
        \Log::info(json_encode($data));

        $user = $this->user->create(array(
            'id'           => $data['id'],
            'name'         => $data['name'],
            'email'        => $data['email'],
            'cadence'      => $data['cadence'],
            'active_until' => isset($data['active_until']) ? $data['active_until'] : null,
            'password'     => bcrypt($data['password']),
        ));

        if( ! $user ){
            return false;
        }

        return $user;
    }

    public function makeOrUpdate($data)
    {
        return $this->user->updateOrCreate(
            ['id' => $data['id']],
            [
                'id'       => $data['id'],
                'name'     => $data['name'],
                'email'    => $data['email'],
                'active_until' => isset($data['active_until']) ? $data['active_until'] : null,
                'cadence'  => isset($data['cadence']) ? $data['cadence'] : 'weekly',
                'password' => bcrypt($data['password']),
            ]
        );
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
