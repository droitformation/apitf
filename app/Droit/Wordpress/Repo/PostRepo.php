<?php namespace App\Droit\Wordpress\Repo;

use \App\Droit\Wordpress\Entites\Post as Post;

class PostRepo
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getBySlug($slug)
    {
        return $this->post->where('post_name', '=', $slug)->firstOrFail();
    }

    public function getPostByCategory($id)
    {
        return $this->post->taxonomy('category',$id)->with(['taxonomies' => function ($query) {
            $query->where('taxonomy', '=', 'annee');
        }])->orderBy('post_date','DESC')->paginate(10);
    }

    public function getPostByCategoryAndYear($id,$year)
    {
        return $this->post->join('term_relationships', 'posts.ID', '=', 'term_relationships.object_id')
            ->join('term_taxonomy', 'term_relationships.term_taxonomy_id','=', 'term_taxonomy.term_taxonomy_id')
            ->join('terms', 'term_taxonomy.term_id', '=', 'terms.term_id')
            ->year($year)
            ->taxonomy('category',$id)->orderBy('post_date','DESC')->paginate(10);
    }

    public function search($terms)
    {
        return $this->post->search($terms)->published()->orderBy('post_date','DESC')->paginate(10);
    }

    public function searchLaw($laws)
    {
        return $this->post
            ->join('postmeta', 'posts.ID','=', 'postmeta.post_id')
            ->where(function ($query) use ($laws) {
                foreach ($laws as $law){
                    $query->where('postmeta.meta_key', '=', 'termes_rechercher')->where('postmeta.meta_value', 'LIKE', '%'.$law.'%');
                }
            })
            ->published()
            ->orderBy('post_date','DESC')
            ->paginate(10);
    }

}