<?php namespace  App\Droit\Categorie\Repo;

use  App\Droit\Categorie\Repo\CategorieKeywordInterface;
use  App\Droit\Categorie\Entities\Categorie_keyword as M;

class CategorieKeywordEloquent implements CategorieKeywordInterface{

    protected $categorie;

    public function __construct(M $categorie)
    {
        $this->categorie = $categorie;
    }

    public function getAll()
    {
        return $this->categorie->all();
    }

    public function find($id){

        return $this->categorie->findOrFail($id);
    }

    public function create(array $data){

        $categorie = $this->categorie->create(array(
            'keywords'     => $data['keywords'],
            'categorie_id' => $data['categorie_id'],
        ));

        if( ! $categorie ) {
            return false;
        }

        return $categorie;
    }

    public function update(array $data){

        $categorie = $this->categorie->findOrFail($data['id']);

        if( ! $categorie ) {
            return false;
        }

        $categorie->fill($data);
        $categorie->save();

        return $categorie;
    }

    public function delete($id){

        $categorie = $this->categorie->find($id);

        return $categorie->delete();
    }

}
