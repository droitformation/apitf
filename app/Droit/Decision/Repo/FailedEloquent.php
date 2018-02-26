<?php namespace  App\Droit\Decision\Repo;

use  App\Droit\Decision\Repo\FailedInterface;
use  App\Droit\Decision\Entities\Failed as M;

class FailedEloquent implements FailedInterface{

    protected $failed;

    public function __construct(M $failed)
    {
        $this->failed = $failed;
    }

    public function getAll()
    {
        return $this->failed->all();
    }

    public function create(array $data){

        $failed = $this->failed->create(array(
            'publication_at' => isset($data['publication_at']) ? $data['publication_at'] : \Carbon\Carbon::now()->toDateTimeString(),
            'numero'         => isset($data['numero']) ? $data['numero'] : 'oho',
            'created_at'     => \Carbon\Carbon::now(),
            'updated_at'     => \Carbon\Carbon::now()
        ));

        if( ! $failed ) {
            return false;
        }

        return $failed;

    }

    public function update(array $data){

        $failed = $this->failed->findOrFail($data['id']);

        if( ! $failed )
        {
            return false;
        }

        $failed->fill($data);
        $failed->save();

        return $failed;
    }

    public function delete($id){

        $failed = $this->failed->find($id);

        return $failed->delete();
    }

}
