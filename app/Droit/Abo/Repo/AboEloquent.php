<?php namespace  App\Droit\Abo\Repo;

use  App\Droit\Abo\Repo\AboInterface;
use  App\Droit\Abo\Entities\Abo as M;
use  App\Droit\Abo\Entities\Abo_publish as P;

class AboEloquent implements AboInterface{

    protected $abo;
    protected $publish;

    public function __construct(M $abo, P $publish)
    {
        $this->abo     = $abo;
        $this->publish = $publish;
    }

    public function getAll()
    {
        return $this->abo->all();
    }

    public function find($id)
    {
        return $this->abo->with(['abos'])->find($id);
    }

    public function publish($catgorie_id,$user_id){

        $publish = $this->publish->create(array(
            'user_id'      => $user_id,
            'categorie_id' => $catgorie_id
        ));
    }

    public function unpublish($catgorie_id,$user_id){

        $publish = $this->publish->where('categorie_id','=',$catgorie_id)->where('user_id','=',$user_id)->first();

        if($publish){
            $publish->delete();
        }
    }
    
    public function create(array $data)
    {
        $abo = $this->abo->create(array(
            'user_id'      => $data['user_id'],
            'categorie_id' => $data['categorie_id'],
            'keywords'     => isset($data['keywords']) ? $data['keywords'] : null
        ));

        if( !$abo ) {
            return false;
        }

        return $abo;
    }

    public function update(array $data){

        $abo = $this->abo->findOrFail($data['id']);

        if( ! $abo )
        {
            return false;
        }


        $abo->fill($data);
        $abo->save();

        return $abo;
    }

    public function delete($data){

        $this->abo->where('user_id','=',$data['user_id'])->where('categorie_id','=',$data['categorie_id'])->delete();
        $this->publish->where('user_id','=',$data['user_id'])->where('categorie_id','=',$data['categorie_id'])->delete();
        
        return true;
    }
}
