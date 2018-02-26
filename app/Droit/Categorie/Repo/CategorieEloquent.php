<?php namespace  App\Droit\Categorie\Repo;

use  App\Droit\Categorie\Repo\CategorieInterface;
use  App\Droit\Categorie\Entities\Categorie as M;
use  App\Droit\Categorie\Entities\Parent_categorie as P;

class CategorieEloquent implements CategorieInterface{

    protected $categorie;
    protected $parent;

    public function __construct(M $categorie, P $parent)
    {
        $this->categorie = $categorie;
        $this->parent    = $parent;
    }

    public function getAll()
    {
        return $this->categorie->with(['parent'])->get();
    }

    public function getGlobal()
    {
        return $this->categorie->whereNotNull('global')->orderBy('name')->get();
    }

    public function getParents()
    {
        return $this->parent->with(['categories'])->orderBy('nom')->get();
    }

    public function searchByName($name)
    {
        // if we have a variant like "(general)" or "(en general)" test it
        $find = ' (en ';
        $pos  = strpos($name, $find);

        // Select categorie where the string provided sounds the same
        $query = 'soundex(name)=soundex("'.$name.'")';

        if($pos)
        {
            $without = str_replace($find, ' (', $name);
            $query   .= ' OR soundex(name)=soundex("'.$without.'")';
        }

        $query .= ' OR soundex(name_de)=soundex("'.$name.'") OR soundex(name_it)=soundex("'.$name.'")';

        $categorie = $this->categorie->whereRaw($query)->get();

        return !$categorie->isEmpty() ? $categorie->first() : null;
    }

    public function find($id){

        return $this->categorie->with(['arrets'])->findOrFail($id);
    }

    public function create(array $data){

        $categorie = $this->categorie->create(array(
            'name'      => $data['name'],
            'name_de'   => $data['name_de'],
            'name_it'   => $data['name_it'],
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : 0,
            'rang'      => isset($data['rang']) ? $data['rang'] : 0,
            'general'   => isset($data['general']) ? $data['general'] : '',
        ));

        if( ! $categorie )
        {
            return false;
        }

        return $categorie;

    }

    public function update(array $data){

        $categorie = $this->categorie->findOrFail($data['id']);

        if( ! $categorie )
        {
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
