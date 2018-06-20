<?php namespace App\Droit\Transfert\Site\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model{

    use SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $table    = 'sites';
    protected $fillable = ['nom','url','logo','slug','prefix'];

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

}