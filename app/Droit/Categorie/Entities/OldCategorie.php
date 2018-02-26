<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class OldCategorie extends Model {

    protected $table = 'wp_subcategories';
    protected $primaryKey = 'term_id';
    protected $connection = 'mysql';

	//protected $fillable = ['term_id','name','name_de','name_it','terme_parent','rang','general'];
    //protected $fillable = ['term_id','nom'];
    protected $fillable = ['term_id','name','refCategorie','refNouveaute'];

    public $timestamps  = false;

}