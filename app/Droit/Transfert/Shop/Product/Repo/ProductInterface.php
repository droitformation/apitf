<?php namespace App\Droit\Shop\Product\Repo;

interface ProductInterface {

	public function getAll($search = null, $nbr = null, $hidden = false);
	public function getNbr($nbr = null, $hidden = false);
	public function getList($terms,$admin = false);
    public function getSome($ids);
	public function listForAdminOrder();
	public function forAbos($admin = null);
	public function getAbos();
	public function getByCategorie($id);
	public function search($term, $hidden = false);
	public function find($data);
	public function sku($product_id, $qty, $operator);
	public function create(array $data);
	public function update(array $data);
	public function delete($id);

}

