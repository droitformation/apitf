<?php namespace App\Droit\Transfert\Arret\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arret extends Model {

    use SoftDeletes;
    protected $connection = 'transfert';

	protected $fillable = ['site_id','user_id','reference','pub_date','abstract','pub_text','file','dumois'];
    protected $dates    = ['pub_date'];

    public function categories()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Categorie\Entities\Categorie', $database.'.arret_categories', 'arret_id', 'categories_id');
    }

    public function analyses()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Analyse\Entities\Analyse', "$database.analyses_arret", 'arret_id', 'analyse_id');

       // return $this->belongsToMany('\App\Droit\Transfert\Analyse\Entities\Analyse', 'analyses_arret', 'arret_id', 'analyse_id');
    }

}
