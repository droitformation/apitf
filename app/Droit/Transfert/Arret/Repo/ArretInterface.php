<?php namespace App\Droit\Transfert\Arret\Repo;

interface ArretInterface {

    public function getAll($site = null, $exclude = null);
    public function getCount($site = null);
    public function getLast($nbr,$site);
    public function annees($site);
    public function allForSite($site, $options);
    public function getAllActives($exclude = [], $site = null);
    public function getPaginate($nbr);
    public function getLatest($exclude = []);
    public function find($id, $trashed = null);
    public function findyByImage($file);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
