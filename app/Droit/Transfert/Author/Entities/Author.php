<?php namespace App\Droit\Transfert\Author\Entities;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

	protected $fillable = ['first_name','last_name','occupation','bio','photo','rang'];
    protected $connection = 'transfert';
    public $timestamps  = false;

    public function analyses()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Analyse\Entities\Analyse', $database.'.analyse_authors', 'analyse_id', 'author_id');
    }

}

