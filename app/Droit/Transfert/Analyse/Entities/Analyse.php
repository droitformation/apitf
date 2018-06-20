<?php namespace App\Droit\Transfert\Analyse\Entities;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model {

    protected $table    = 'analyses';
    protected $fillable = ['user_id', 'pub_date','abstract','file','site_id','title'];
    protected $dates    = ['pub_date','created_at','updated_at'];
    protected $connection = 'transfert';

    public function categories()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Categorie\Entities\Categorie', $database.'.analyse_categories', 'analyse_id', 'categories_id');
    }
    
	public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Arret\Entities\Arret', $database.'.analyses_arret', 'analyse_id', 'arret_id');
    }

    public function authors()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Author\Entities\Author', $database.'.analyse_authors', 'analyse_id', 'author_id');
    }
}
