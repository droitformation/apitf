<?php namespace App\Droit\User\Repo;

interface UserInterface {

    public function getAll();
    public function find($id);
    public function makeOrUpdate($data);
    public function getByCadence($cadence);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}
