<?php namespace App\Droit\Wordpress\Repo;

use \App\Droit\Wordpress\Entites\Taxonomy as Taxonomy;
use \App\Droit\Wordpress\Entites\Term as Term;

class TaxonomyRepo
{
    protected $taxonomy;
    protected $term;

    public function __construct(Taxonomy $taxonomy, Term $term)
    {
        $this->taxonomy = $taxonomy;
        $this->term = $term;
    }

    public function getTopCategories()
    {
        $top_categories = $this->taxonomy->where('taxonomy', 'category')
            ->where('count','>',0)
            ->whereNotIn('terms.term_id',[3])
            ->where('parent','=',0)
            ->join('terms', 'term_taxonomy.term_id', '=', 'terms.term_id')
            ->orderBy('terms.name','ASC')
            ->get();

        return !$top_categories->isEmpty() ? $top_categories->pluck('term') : collect([]);
    }

    public function getCategorieBySlug($id)
    {
        $categories = $this->taxonomy->category()->slug($id)->get();

        return !$categories->isEmpty() ? $categories->first() : null;
    }

    public function getCategoryWithChildren($id)
    {
        return $this->term->with(['children','allChildrenAccounts'])->where('slug','=',$id)->firstOrFail();
    }

    public function getYears()
    {
        $years = $this->taxonomy->where('taxonomy', 'annee')->get();

        return !$years->isEmpty() ? $years->pluck('term.name','term.slug') : null;
    }
}