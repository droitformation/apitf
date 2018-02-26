<?php namespace  App\Droit\Categorie\Repo;

interface CategorieInterface {

    public function getAll();
    public function getParents();
    public function getGlobal();
    public function searchByName($name);
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
