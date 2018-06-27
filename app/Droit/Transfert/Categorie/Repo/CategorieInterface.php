<?php namespace  App\Droit\Transfert\Categorie\Repo;

interface CategorieInterface {

    public function getAll($site = null);
    public function getAllMain();
    public function getAllOnSite();
    public function find($data);
    public function findyByImage($file);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}
