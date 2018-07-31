<?php namespace App\Droit\Transfert\Page\Repo;

interface PageInterface {

    public function getAll($site = null);
    public function getRoot($site = null);
    public function getTree($key = null, $seperator = '  ',$site = null);
    public function find($id);
    public function search($term);
    public function buildTree($data);
    public function getBySlug($site,$slug);
    public function create(array $data);
    public function update(array $data);
    public function updateSorting(array $data);
    public function delete($id);

}
