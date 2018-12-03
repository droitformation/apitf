<?php namespace App\Droit\Transfert\Site\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model{

    use SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $table    = 'sites';
    protected $fillable = ['nom','url','logo','slug','prefix'];
    protected $connection = 'transfert';

    public function arrets()
    {
        return $this->hasMany('App\Droit\Transfert\Arret\Entities\Arret');
    }

    public function analyses()
    {
        return $this->hasMany('App\Droit\Transfert\Analyse\Entities\Analyse');
    }

    public function categories()
    {
        return $this->hasMany('App\Droit\Transfert\Categorie\Entities\Categorie');
    }

    public function newsletter()
    {
        return $this->hasMany('App\Droit\Transfert\Newsletter\Entities\Newsletter')->first();
    }

    public function authors()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Author\Entities\Author', $database.'.authors_sites', 'site_id', 'author_id');
    }
}
