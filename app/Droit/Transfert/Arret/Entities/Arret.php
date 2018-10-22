<?php namespace App\Droit\Transfert\Arret\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arret extends Model {

    use SoftDeletes;
    protected $connection = 'transfert';

	protected $fillable = ['site_id','user_id','reference','pub_date','abstract','pub_text','file','dumois'];
    protected $dates    = ['pub_date'];

    public function getFilterAttribute()
    {
        return $this->categories->map(function ($categorie, $key) {
            return 'c'.$categorie->id;
        })->implode(' ');
    }

    public function getTheTitleAttribute()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        return $this->reference.' du '.$this->pub_date->formatLocalized('%d %B %Y');
    }

    public function scopeCategories($query, $categories)
    {
        if(!empty($categories)) {
            foreach($categories as $categorie) {
                $query->whereHas('categories', function($query) use ($categorie){
                    $query->where('categories_id', '=' ,$categorie);
                });
            }
        }
    }

    public function scopeYears($query, $years)
    {
        if(!empty($years)) {
            $query->whereIn(\DB::raw("year(pub_date)"), $years)->get();
        }
    }

    public function categories()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Categorie\Entities\Categorie', $database.'.arret_categories', 'arret_id', 'categories_id')
            ->withPivot('sorting');
    }

    public function analyses()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Transfert\Analyse\Entities\Analyse', "$database.analyses_arret", 'arret_id', 'analyse_id');
    }

    public function contents()
    {
        return $this->hasMany('App\Droit\Transfert\Newsletter\Entities\Newsletter_contents', 'arret_id');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Transfert\Site\Entities\Site');
    }

}
