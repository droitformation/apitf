<?php namespace App\Droit\Transfert\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_contents extends Model {

	protected $fillable = ['type_id','titre','contenu','image','lien','arret_id','categorie_id','product_id','colloque_id','newsletter_campagne_id','rang','groupe_id'];

    public $timestamps = false;
    protected $connection = 'transfert';

    public function campagne(){

        return $this->belongsTo('App\Droit\Transfert\Newsletter\Entities\Newsletter_campagnes');
    }

    public function newsletter(){

        return $this->belongsTo('App\Droit\Transfert\Newsletter\Entities\Newsletter');
    }

    public function type(){

        return $this->belongsTo('App\Droit\Transfert\Newsletter\Entities\Newsletter_types');
    }

    public function categorie(){

        return $this->belongsTo('App\Droit\Transfert\Categorie\Entities\Categorie');
    }

    public function arret()
    {
        return $this->hasOne('App\Droit\Transfert\Arret\Entities\Arret', 'id', 'arret_id');
    }

    public function groupe()
    {
        return $this->hasOne('App\Droit\Transfert\Arret\Entities\Groupe', 'id', 'groupe_id');
    }

}
