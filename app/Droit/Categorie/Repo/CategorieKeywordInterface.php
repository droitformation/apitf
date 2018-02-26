<?php namespace  App\Droit\Categorie\Repo;

interface CategorieKeywordInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
