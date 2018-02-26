<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Parent_categorie extends Model {

	protected $fillable = ['parent_categories'];
    
    public $timestamps  = false;

    public function categories()
    {
        return $this->hasMany('App\Droit\Categorie\Entities\Categorie', 'parent_id', 'id');
    }
}