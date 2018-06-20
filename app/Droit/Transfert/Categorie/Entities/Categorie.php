<?php namespace App\Droit\Transfert\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model {

    use SoftDeletes;

	protected $fillable = ['title','image','site_id','ismain','hideOnSite','created_at'];
    protected $dates    = ['created_at','updated_at','deleted_at'];
    protected $connection = 'transfert';

    public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Arret\Entities\Arret', $database.'.arret_categories', 'categories_id', 'arret_id');
    }
}