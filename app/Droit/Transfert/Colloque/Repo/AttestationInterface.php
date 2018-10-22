<?php namespace App\Droit\Colloque\Repo;

interface AttestationInterface {

    public function getAll();
    public function find($data);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);

}