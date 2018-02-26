<?php namespace App\Droit\Wordpress\Entites;

use Illuminate\Database\Eloquent\Model;
use \Corcel\Model\Term as Corcel;

class Term extends Corcel
{
    public function children()
    {
        return $this->hasMany('App\Droit\Wordpress\Entites\Taxonomy', 'parent', 'term_id')
            ->join('terms', 'term_taxonomy.term_id', '=', 'terms.term_id')
            ->groupBy('terms.term_id')
            ->orderBy('terms.name','ASC');
    }

    public function allChildrenAccounts()
    {
        return $this->children()->with('allChildrenAccounts');
    }
}
