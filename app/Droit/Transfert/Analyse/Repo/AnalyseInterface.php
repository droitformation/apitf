<?php namespace App\Droit\Transfert\Analyse\Repo;

interface AnalyseInterface {

    public function getAll($site = null,$exclude = []);
	public function allForSite($site, $years = null);
	public function getCount($site = null);
	public function getLast($nbr,$site);
	public function find($data);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}
