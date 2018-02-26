<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class ArchiveCategorie extends Model {

    protected $table = 'wp_subcategories';
    protected $primaryKey = 'term_id';
    protected $connection = 'sqlite';

	protected $fillable = ['term_id','name','refCategorie','refNouveaute'];
    
    public $timestamps  = false;

}