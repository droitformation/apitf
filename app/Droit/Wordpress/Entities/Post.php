<?php namespace App\Droit\Wordpress\Entites;

use Illuminate\Database\Eloquent\Model;
use \Corcel\Model\Post as Corcel;

class Post extends Corcel {

    public function getContentAttribute()
    {
        return wpautop($this->post_content);
    }

    public function getTitleLinkAttribute()
    {
        return $this->meta->atf ? $this->meta->atf : $this->post_title;
    }

    public function getAnneeAttribute()
    {
        if(!$this->taxonomies->isEmpty()){
            return $this->taxonomies->first()->term;
        }
    }

    /**
     * Filter by post annee
     */
    public function scopeYear($query, $annee = null) {
        if($annee){
            return $query->where(function ($query) use ($annee) {
                $query->where('term_taxonomy.taxonomy', '=' ,'annee')->where('terms.slug', '=', $annee);
            });
        }
    }

    public function scopeBySlug($query, $slug) {
        return $query->where('post_name', '=' ,$slug);
    }

    public function scopeSearch($query,$terms)
    {
        if($terms && !empty($terms))
        {
            foreach($terms as $term)
            {
                $query->where('post_content','LIKE',$term)->orWhere('post_title','LIKE',$term);
            }
        };
    }


    /**
     * Filter by post status
     */
    public function scopeStatus($query, $status = 'publish') {
        return $query->where('post_status', '=', $status);
    }

    //Method to get post by array of categories
    static function getPostsByCategories(array $categories, $annee = null) {

        return Post::status()->type()
                ->with(['annee','postmetas' => function ($query) {
                    $query->where('meta_key', '=', 'atf')->orWhere('meta_key', '=', 'auteur');
                }])
                ->join('wp_term_relationships', 'wp_posts.ID', '=', 'wp_term_relationships.object_id')
                ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id','=', 'wp_term_taxonomy.term_taxonomy_id')
                ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
               // ->categories($categories)
                ->where('wp_term_taxonomy.taxonomy', '=' ,'category')
                ->whereIn('wp_term_taxonomy.term_id', $categories)
                //->annee($annee)
                /*->whereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('term_id', $categories);
                })*/
                ->orderBy('post_date', 'DESC')
                //->toSql();
                ->paginate(5);
    }

}