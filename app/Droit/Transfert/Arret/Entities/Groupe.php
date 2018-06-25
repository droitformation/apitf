<?php namespace App\Droit\Transfert\Arret\Entities;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model {

    /**
     * Set timestamps off
     */
    public $timestamps = false;

	protected $fillable = ['categorie_id'];
    protected $connection = 'transfert';

    public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Arret\Entities\Arret', $database.'.arrets_groupes', 'groupe_id', 'arret_id');
    }

    public function categorie()
    {
        return $this->hasOne('\App\Droit\Transfert\Categorie\Entities\Categorie','id', 'categorie_id');
    }
}
