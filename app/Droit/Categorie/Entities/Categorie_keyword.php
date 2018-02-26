<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;

class Categorie_keyword extends Model {

    protected $table = 'categorie_keywords';
	protected $fillable = ['keywords','categorie_id'];
    
    public $timestamps  = false;

    public function getKeywordsListAttribute()
    {
        $search = htmlspecialchars_decode($this->keywords);

        return collect(explode(',',$search))->groupBy(function ($item, $key) {
            return preg_match('/\"([^\"]*?)\"/', $item, $m) ? 'quotes' : 'noquotes';
        })->map(function ($items, $key) {
            return $items->map(function ($item, $key) {
                return trim(str_replace('"', '', $item));
            });
        })->flatten(1);
    }

    public function categorie()
    {
        return $this->hasOne('App\Droit\Categorie\Entities\Categorie');
    }
}