<?php namespace App\Droit\Transfert\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Parent_categorie extends Model {

	protected $fillable = ['title','image'];
    public $timestamps  = false;
    protected $table    = 'parent_categories';
}