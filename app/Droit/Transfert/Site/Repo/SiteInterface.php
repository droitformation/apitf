<?php namespace App\Droit\Transfert\Site\Repo;

interface SiteInterface {

    public function getAll();
    public function find($data);
    public function findBySlug($slug);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}