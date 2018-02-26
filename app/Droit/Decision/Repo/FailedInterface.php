<?php namespace App\Droit\Decision\Repo;

interface FailedInterface {

    public function getAll();
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}
