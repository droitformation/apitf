<?php

namespace App\Droit\Decision\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Droit\Decision\Traits\FullTextSearch;

class Decision extends Model
{
    use FullTextSearch;

    protected $table = 'decisions';
    protected $dates = ['publication_at','decision_at'];


    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'texte'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'publication_at', 'decision_at', 'categorie_id', 'remarque', 'link', 'numero', 'texte', 'langue', 'publish', 'updated'
    ];

    public function getLangAttribute()
    {
        return $this->langue == 0 ? 'Français' : 'Allemand';
    }

    public function scopeSearch($query,$terms)
    {
        if($terms && !empty($terms)) {
            foreach($terms as $term) {
                $term = addslashes($term);
                $term = str_replace(';',' ',$term);
                //$query->whereRaw("MATCH (texte) AGAINST ('$term')");
                $query->where(function ($q) use ($term) {
                    $q->where('texte','LIKE','% '.$term.' %')
                        ->orWhere('texte','LIKE','%'.$term.' %')
                        ->orWhere('texte','LIKE','% '.$term.'%');
                });
            }
        };
    }

    public function scopeSearchxp($query,$terms)
    {
        if($terms && !empty($terms)) {
            foreach($terms as $term) {
                $term = addslashes($term);
                $term = str_replace(';',' ',$term);
                $query->whereRaw('texte REGEXP  "[[:<:]]'.$term.'[[:>:]]"');
            }
        };
    }

    public function scopeCategorie($query,$categories)
    {
        if (isset($categories) && !empty($categories)) $query->whereHas('categorie', function ($query) use ($categories) {
            $query->where('categorie_id', '=' ,$categories);
        });
    }

    public function scopePublished($query, $publish)
    {
        if($publish && $publish > 0){
            $query->where('publish', '=' ,1);
        }
    }

    public function scopePublicationAt($query, $publication_at)
    {
        if($publication_at) {
            $publication_at = is_array($publication_at) ? $publication_at : [$publication_at];

            $query->where(function ($q) use ($publication_at) {
                foreach ($publication_at as $date){
                    $q->orWhereDate('publication_at', '=' ,$date);
                }
            });
        }
    }

    public function getPublicationListDateAttribute()
    {
        return $this->publication_at->toDateString();
    }

    public function categorie()
    {
        return $this->belongsTo('App\Droit\Categorie\Entities\Categorie');
    }

    public function other_categories()
    {
        return $this->belongsToMany('App\Droit\Categorie\Entities\Categorie','decision_categories','decision_id','categorie_id');
    }
}
