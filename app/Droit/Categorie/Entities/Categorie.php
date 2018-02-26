<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model {

	protected $fillable = ['name','name_de','name_it','parent_id','rang','general'];
    
    public $timestamps  = false;

    public function parent()
    {
        return $this->belongsTo('App\Droit\Categorie\Entities\Parent_categorie');
    }
}